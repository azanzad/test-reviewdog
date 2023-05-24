/**
 * Analytics Dashboard
 */

'use strict';
(function () {
  let cardColor, headingColor, axisColor, borderColor, shadeColor;

  if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    headingColor = config.colors_dark.headingColor;
    axisColor = config.colors_dark.axisColor;
    borderColor = config.colors_dark.borderColor;
    shadeColor = 'dark';
  } else {
    cardColor = config.colors.white;
    headingColor = config.colors.headingColor;
    axisColor = config.colors.axisColor;
    borderColor = config.colors.borderColor;
    shadeColor = 'light';
  }

  // Report Chart
  // --------------------------------------------------------------------

  // Radial bar chart functions
  function radialBarChart(color, value) {
    const radialBarChartOpt = {
      chart: {
        height: 50,
        width: 50,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          hollow: {
            size: '25%'
          },
          dataLabels: {
            show: false
          },
          track: {
            background: borderColor
          }
        }
      },
      stroke: {
        lineCap: 'round'
      },
      colors: [color],
      grid: {
        padding: {
          top: -8,
          bottom: -10,
          left: -5,
          right: 0
        }
      },
      series: [value],
      labels: ['Progress']
    };
    return radialBarChartOpt;
  }

  const ReportchartList = document.querySelectorAll('.chart-report');
  if (ReportchartList) {
    ReportchartList.forEach(function (ReportchartEl) {
      const color = config.colors[ReportchartEl.dataset.color],
        series = ReportchartEl.dataset.series;
      const optionsBundle = radialBarChart(color, series);
      const reportChart = new ApexCharts(ReportchartEl, optionsBundle);
      reportChart.render();
    });
  }

  // Analytics - Bar Chart
  // --------------------------------------------------------------------
  const analyticsBarChartEl = document.querySelector('#analyticsBarChart'),
    analyticsBarChartConfig = {
      chart: {
        height: 250,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '20%',
          borderRadius: 3,
          startingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: [config.colors.primary, config.colors_label.primary],
      series: [
        {
          name: '2020',
          data: [8, 9, 15, 20, 14, 22, 29, 27, 13]
        },
        {
          name: '2019',
          data: [5, 7, 12, 17, 9, 17, 26, 21, 10]
        }
      ],
      grid: {
        borderColor: borderColor,
        padding: {
          bottom: -8
        }
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      yaxis: {
        min: 0,
        max: 30,
        tickAmount: 3,
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      legend: {
        show: false
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return '$ ' + val + ' thousands';
          }
        }
      }
    };
  if (typeof analyticsBarChartEl !== undefined && analyticsBarChartEl !== null) {
    const analyticsBarChart = new ApexCharts(analyticsBarChartEl, analyticsBarChartConfig);
    analyticsBarChart.render();
  }



// Analytics - Bar Chart
  // --------------------------------------------------------------------
  const analyticsBarChart2El = document.querySelector('#analyticsBarChart2'),
    analyticsBarChart2Config = {
      chart: {
        height: 250,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '20%',
          borderRadius: 3,
          startingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: [config.colors.primary, config.colors_label.primary],
      series: [
        {
          name: '2020',
          data: [8, 9, 15, 20, 14, 22, 29, 27, 13]
        },
        {
          name: '2019',
          data: [5, 7, 12, 17, 9, 17, 26, 21, 10]
        }
      ],
      grid: {
        borderColor: borderColor,
        padding: {
          bottom: -8
        }
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      yaxis: {
        min: 0,
        max: 30,
        tickAmount: 3,
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      legend: {
        show: false
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return '$ ' + val + ' thousands';
          }
        }
      }
    };
  if (typeof analyticsBarChart2El !== undefined && analyticsBarChart2El !== null) {
    const analyticsBarChart2 = new ApexCharts(analyticsBarChart2El, analyticsBarChart2Config);
    analyticsBarChart2.render();
  }
  


// Analytics - Bar Chart
  // --------------------------------------------------------------------
  const analyticsBarChart3El = document.querySelector('#analyticsBarChart3'),
    analyticsBarChart3Config = {
      chart: {
        height: 250,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '20%',
          borderRadius: 3,
          startingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: [config.colors.primary, config.colors_label.primary],
      series: [
        {
          name: '2020',
          data: [8, 9, 15, 20, 14, 22, 29, 27, 13]
        },
        {
          name: '2019',
          data: [5, 7, 12, 17, 9, 17, 26, 21, 10]
        }
      ],
      grid: {
        borderColor: borderColor,
        padding: {
          bottom: -8
        }
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      yaxis: {
        min: 0,
        max: 30,
        tickAmount: 3,
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      legend: {
        show: false
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return '$ ' + val + ' thousands';
          }
        }
      }
    };
  if (typeof analyticsBarChart3El !== undefined && analyticsBarChart3El !== null) {
    const analyticsBarChart3 = new ApexCharts(analyticsBarChart3El, analyticsBarChart3Config);
    analyticsBarChart3.render();
  }



// Analytics - Bar Chart
  // --------------------------------------------------------------------
  const analyticsBarChart4El = document.querySelector('#analyticsBarChart4'),
    analyticsBarChart4Config = {
      chart: {
        height: 250,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '20%',
          borderRadius: 3,
          startingShape: 'rounded'
        }
      },
      dataLabels: {
        enabled: false
      },
      colors: [config.colors.primary, config.colors_label.primary],
      series: [
        {
          name: '2020',
          data: [8, 9, 15, 20, 14, 22, 29, 27, 13]
        },
        {
          name: '2019',
          data: [5, 7, 12, 17, 9, 17, 26, 21, 10]
        }
      ],
      grid: {
        borderColor: borderColor,
        padding: {
          bottom: -8
        }
      },
      xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      yaxis: {
        min: 0,
        max: 30,
        tickAmount: 3,
        labels: {
          style: {
            colors: axisColor
          }
        }
      },
      legend: {
        show: false
      },
      tooltip: {
        y: {
          formatter: function (val) {
            return '$ ' + val + ' thousands';
          }
        }
      }
    };
  if (typeof analyticsBarChart4El !== undefined && analyticsBarChart4El !== null) {
    const analyticsBarChart4 = new ApexCharts(analyticsBarChart4El, analyticsBarChart4Config);
    analyticsBarChart4.render();
  }

})();
