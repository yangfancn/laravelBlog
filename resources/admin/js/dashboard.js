import * as echarts from "echarts";
import dayjs from "dayjs";
import axios from "axios";

window.dashboard = function() {
  let chartDistributed = document.getElementById('charts-week-visitor-map'),
    chartVisitor = document.getElementById('charts-month-visitor'),
    chartRobot = document.getElementById('charts-month-spider');
  // 解决 pjax 后退不加载地图
  echarts.dispose(chartDistributed);
  echarts.dispose(chartVisitor);
  echarts.dispose(chartRobot);

  let MapChart = echarts.init(chartDistributed),
    VisitorChart = echarts.init(chartVisitor),
    RobotChart = echarts.init(chartRobot),
    option;

  month_dates = month_dates.map(function (value) {
    return dayjs(value).format('YYYY/MM/DD')
  })

  axios({
    url: '/admin/data/map/china.json',
    method: 'GET',
    dataType: 'JSON',
  }).then(function (response) {
    MapChart.hideLoading();
    echarts.registerMap('China', response.data);
    let maxValue = Math.max.apply(null, visitors.map(item => item.value));
    option = {
      tooltip: {
        trigger: 'item',
        showDelay: 0,
        transitionDuration: 0.2,
        formatter: function (params) {
          let value = (params.value + '').split('.');
          value = value[0].replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,');
          return params.seriesName + '<br/>' + params.name + ' : ' + (value === 'NaN' ? 0 : value);
        }
      },
      visualMap: {
        left: 'right',
        min: 0,
        max: maxValue === 0 ? 10 : maxValue,
        inRange: {
          color: ['#313695', '#4575b4', '#74add1', '#abd9e9', '#e0f3f8', '#ffffbf', '#fee090', '#fdae61', '#f46d43', '#d73027', '#a50026']
        },
        text: ['High', 'Low'],           // 文本，默认为数值文本
        calculable: true,
        textStyle: {
          color: '#fff'
        }
      },
      toolbox: {
        show: false,
      },
      series: [
        {
          name: '访问量分布',
          type: 'map',
          roam: true,
          map: 'China',
          emphasis: {
            label: {
              show: true
            }
          },
          data: visitors,
        }
      ]
    };
    option && MapChart.setOption(option, true);
  })

  VisitorChart.setOption(new LineOption(formatData(month_visitors)), true);
  RobotChart.setOption(new LineOption(formatData(month_spiders)), true);

  jQuery(window).resize(function () {
    MapChart.resize();
    VisitorChart.resize();
    RobotChart.resize();
  })
}

const names = {
  page_view: 'PV',
  unique_view: 'UV',
  baidu_spider: '百度蜘蛛',
  google_spider: '谷歌蜘蛛',
  '360_spider': '360蜘蛛',
  bing_spider: '必应蜘蛛',
  sougo_spider: '搜狗蜘蛛',
  soso_spider: '搜搜蜘蛛',
  byte_spider: '头条蜘蛛',
  other_spider: '其他蜘蛛'
}

function LineOption(data) {
  this.tooltip = {
    trigger: 'axis',
    axisPointer: {
      type: 'cross',
      label: {
        backgroundColor: '#6a7985'
      }
    }
  };
  this.legend = {
    data: data.legend
  };
  this.toolbox = {
    feature: {
      saveAsImage: {}
    }
  };
  this.grid = {
    left: '3%',
    right: '4%',
    bottom: '3%',
    containLabel: true
  };
  this.xAxis = [
    {
      type: 'category',
      boundaryGap: false,
      data: data.xData
    }
  ];
  this.yAxis = [
    {
      type: 'value'
    }
  ];
  this.series = function () {
    let series = [];
    for (const index in data.seriesData) {
      if (!data.seriesData.hasOwnProperty(index)) {
        break;
      }
      series.push({
        name: data.legend[index],
        type: 'line',
        areaStyle: {},
        emphasis: {
          focus: 'series'
        },
        data: data.seriesData[index],
        smooth: true
      })
    }
    return series;
  }();
}

function formatData(data) {
  const result = {
    legend: [],
    seriesData: [],
    xData: month_dates
  };
  jQuery.each(data, function (index, item) {
    let i = 0;
    jQuery.each(item, function (key, value) {
      if (index === 0) {
        result.legend.push(names[key])
      }
      if (!result.seriesData.hasOwnProperty(i)) {
        result.seriesData[i] = [];
      }
      result.seriesData[i].push(value)
      ++i;
    })
  })
  return result;
}

