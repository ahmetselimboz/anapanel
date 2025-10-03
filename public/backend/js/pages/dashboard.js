//[Dashboard Javascript]

//Project:	Master Admin - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';

		var options = {
          series: [{
            name: "Expenses",
            data: [30, 41, 20, 51, 80, 60]
        }],
          chart: {
          height: 290,
          type: 'area',
			  foreColor:"#bac0c7",
          zoom: {
            enabled: false
          }
        },
		colors:['#EA5455'],
        fill: {
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            inverseColors: false,
            opacityFrom: 0.5,
            opacityTo: 0,
            stops: [0, 90, 100]
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          curve: 'smooth'
        },
        grid: {
          row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
          },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        }
        };

        var chart = new ApexCharts(document.querySelector("#chart-main"), options);
        chart.render();




	var options = {
        series: [
			{
            name: "Current year",
            data: [0, 40, 110, 70, 100, 60, 130, 55, 140, 125]
        	},
			{
            name: "Last year",
            data: [0, 30, 150, 40, 90, 80, 70, 45, 110, 105]
        	},
				],
        chart: {
			foreColor:"#bac0c7",
          height: 300,
          type: 'line',
          zoom: {
            enabled: false
          }
        },
		colors:['#7367F0', '#EA5455'],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          	show: true,
			curve: 'smooth',
			lineCap: 'butt',
			colors: undefined,
			width: 4,
			dashArray: 0,
        },
		 legend: {
		  show: true,
		  position: 'top',
		  horizontalAlign: 'center',
		 },
		markers: {
			size: 6,
			colors: ['#7367F0', '#EA5455'],
			strokeColors: '#ffffff',
			strokeWidth: 2,
			strokeOpacity: 1,
			strokeDashArray: 0,
			fillOpacity: 1,
			discrete: [],
			shape: "circle",
			radius: 5,
			offsetX: 0,
			offsetY: 0,
			onClick: undefined,
			onDblClick: undefined,
			hover: {
			  size: undefined,
			  sizeOffset: 3
			}
		},
        grid: {
			borderColor: '#f7f7f7',
          row: {
            colors: ['transparent'], // takes an array which will be repeated on columns
            opacity: 0
          },
		  yaxis: {
			lines: {
			  show: true,
			},
		  },
        },
        xaxis: {
          categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
		  labels: {
			show: true,
          },
          axisBorder: {
            show: true
          },
          axisTicks: {
            show: true
          },
          tooltip: {
            enabled: true,
          },
        },
        yaxis: {
          labels: {
            show: true,
            formatter: function (val) {
              return val + "K";
            }
          }

        },
      };
      var chart = new ApexCharts(document.querySelector("#charts_widget_2_chart"), options);
      chart.render();



	var options = {
          series: [{
          name: 'Check in',
          data: [76, 85, 101, 98, 87, 105, 91]
        }, {
          name: 'Check Out',
          data: [44, 55, 57, 56, 61, 58, 63]
        }],
          chart: {
          type: 'bar',
		  foreColor:"#bac0c7",
          height: 300,
			  toolbar: {
        		show: false,
			  }
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '50%',
          },
        },
        dataLabels: {
          enabled: false,
        },
		grid: {
			show: false,
		},
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
		colors: ['#7367F0', '#EA5455'],
        xaxis: {
          categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug'],

        },
        yaxis: {

        },
		 legend: {
      		show: false,
		 },
        fill: {
          opacity: 1
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return "$ " + val + " thousands"
            }
          },
			marker: {
			  show: false,
		  },
        }
        };

        var chart = new ApexCharts(document.querySelector("#recent_trend"), options);
		chart.render();



	$('.owl-carousel').owlCarousel({
			loop: true,
			margin: 10,
			responsiveClass: true,
			autoplay: true,
		    dots: false,
			responsive: {
			  0: {
				items: 1,
				nav: false
			  },
			  600: {
				items: 3,
				nav: false
			  },
			  1000: {
				items: 3,
				nav: false,
				margin: 20
			  }
			}
		  });


	WeatherIcon.add('icon1'	, WeatherIcon.SLEET , {stroke:false , shadow:false , animated:true } );

}); // End of use strict
