<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	if($this->session->tempdata('mail_id'))
	{
		$aa=$this->session->tempdata('mail_id');
		$mail_id=$this->encryption->decrypt($aa);
		$this->session->set_tempdata('mail_id',$aa,TIMEOUT);
	}
	else
	{	
		$this->session->set_flashdata("message","Time Out. Login Again.");
		redirect('user_home/logout');
	}
	$user=$this->login_database->read_user_info($mail_id);
	$this->load->helper('inflector');
		$name=$user['first_name'] . '_' . $user['last_name'];
	$name=humanize($name);
	$data=array('title'=>'Progress');
	$this->load->view('template/header',$data);
	$data=array('name'=>$name);
	$this->load->view('template/user_menu',$data);
	
	$category= $this->model_home->category();
	$array_val=array();
	$array_val[]=array();
	$cdarr=array();
	$cdarr[]=array();
	$lbls=array();
	foreach($category as $cat)
	{
		$c=$cat['category_id'];
		$lbls[]=$cat['title'];
		$array_val[$c]=$this->model_progress->cat_prog($mail_id,$c);
		$cdarr[$c]=$this->model_progress->cat_prog_dates($mail_id,$c);
	}
	$piearr=$this->model_progress->strength($mail_id);
	if(!isset($piearr))
	{
		$piearr[]=0;
	}	
	$parr=$this->model_progress->marks($mail_id);
	if(!isset($parr))
	{
		$parr[]=0;
	}
	$darr=$this->model_progress->dates($mail_id,$lbls);
	if(!isset($darr))
	{
		$darr[]="";
	}
	
	$d1=array();
	$d1=$this->model_progress->weeks($mail_id);
	$week_val=array();
	$week_val[]=array();
	$week_lbl=array();
	$week_lbl[]=array();
	for($i=1;$i<sizeof($d1)-1;$i++) 
	{
		$st=$d1[$i];
		$end=$d1[$i+1];
	$week_val[$i]=$this->model_progress->weekwise($mail_id,$st,$end);
	$week_lbl[$i]=$this->model_progress->weekwise_lbl($mail_id,$st,$end,$lbls);	
	}
	
	//$tt=[3,45,6,7,8];
?> 

		<script src="<?PHP echo base_url(); ?>dist/Chart.bundle.js"></script>
    <script src="<?PHP echo base_url(); ?>utils.js"></script>
		<!--external css-->
	<link href="<?PHP echo base_url(); ?>css/chart-style.css" rel="stylesheet">

     


	<section id="main-content">
	<section class="wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h3 class="page-header"><i class="icon_piechart"></i> Progress</h3>
					<?PHP $this->load->view('template/alert'); ?>
			</div>
		
			  <div class="col-lg-12">
                  <section class="panel">
                      <div class="panel-body">
                      <div class="row">  
					  
                       <?php// print_r($array_val);?>
						<!--Progress Line-->
						
	<div class="row">
		<div class="col-md-offset-1 btn-row">
			<div class="btn-group" >
				<button type="button" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" id="overall">
						Overall Progress
				</button>
				<!--<button type="button" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" id="1">
						Aptitude
				</button>-->
				<?PHP foreach($category as $cat): ?>
					<button type="button" id="<?= $cat['category_id'];?>" class="btn btn-primary btn-lg accordion-toggle" data-toggle="collapse" data-parent="#accordion" >
						<?= $cat['title']; ?>
					</button>
			  <?PHP   endforeach; ?>
			  </div>
		</div>
	</div>
	
<div id="canvas-holder" style="width: 65%; float: left; ">
								<canvas id="chart"/>
								</div>
					

<div id="canvas-holder" style="width: 25%; float: left; margin:5%; ">
					   Know your Strength
						<canvas id="chart-area" width="100" height="100" />
						</div>
		
		<br>
	<div style="width:75%; margin-top:40%; margin-left:10%;">
	
	<?PHP for($i=1;$i<sizeof($d1)-1;$i++) {?>
		<button type="button" id="Week<?= $i; ?>" class="btn btn-default" data-toggle="collapse" data-parent="#accordion" >
		Week<?= $i;?>
		<?php
		$st=$d1[$i];
		$end=$d1[$i+1];
		echo " (".$st." to ".$end.")";
		?>
		</button>
	<?PHP } ?>
	
	    <canvas id="canvas1" ></canvas>
    </div>
    
	 </div>
                  </div>   
      </section>
                       </div>
                      </div>
                    </section>
              
      </section>
      
	<script>
	
	var config = {
		type: 'pie',
		data: {
			datasets: [{
				data: <?php echo json_encode($piearr); ?> ,
				backgroundColor: [
					window.chartColors.blue,
					window.chartColors.orange,
					window.chartColors.red,
					
				],
			}],
			labels: 	<?php echo json_encode($lbls); ?>
		},
		options: {
			responsive: true,
			legend: {
				display: true
			},
			tooltips: {
				enabled: true,
			}
		}
	};
	
	//weekwise
	var config1 = {
            type: 'line',
            data: {
                labels: <?php echo json_encode($week_lbl[1]); ?>,
                datasets: [{
                    label: "Weekwise Progress",
                    borderColor: window.chartColors.red,
                    backgroundColor: window.chartColors.blue,
                    data: <?php echo json_encode($week_val[1]); ?>,
                    fill: true,
                    steppedLine: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Weekwise Progress'
                },
                tooltips: {
                    mode: 'index',
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Week'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Marks'
                        },
                    }]
                }
            }
        };

	Chart.plugins.register({
            afterDatasetsDraw: function(chartInstance, easing) {
                // To only draw at the end of animation, check for easing === 1
                var ctx = chartInstance.chart.ctx;

                chartInstance.data.datasets.forEach(function (dataset, i) {
                    var meta = chartInstance.getDatasetMeta(i);
                    if (!meta.hidden) {
                        meta.data.forEach(function(element, index) {
                            // Draw the text in black, with the specified font
                            ctx.fillStyle = 'rgb(0, 0, 0)';

                            var fontSize = 16;
                            var fontStyle = 'normal';
                            var fontFamily = 'Helvetica Neue';
                            ctx.font = Chart.helpers.fontString(fontSize, fontStyle, fontFamily);

                            // Just naively convert to string for now
                            var dataString = dataset.data[index].toString();

                            // Make sure alignment settings are correct
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';

                            var padding = -20;
                            var position = element.tooltipPosition();
                            ctx.fillText(dataString, position.x, position.y - (fontSize /2) - padding);
                        });
                    }
                });
            }
        });
	
	window.onload = function() {
		//pie
			var ctx = document.getElementById("chart-area").getContext("2d");
			window.myPie = new Chart(ctx, config);
			//weekwise
			var ctx1 = document.getElementById("canvas1").getContext("2d");
            window.myLine = new Chart(ctx1, config1);
        
			//line
			var chartEl = document.getElementById("chart");
			window.myLine = new Chart(chartEl, {
				type: 'line',
				data: lineChartData,
				options: {
					title:{
						display:true,
						text:'Progress chart'
					},
					tooltips: {
						enabled: true,
						mode: 'index',
						position: 'nearest',
						custom: customTooltips
					},
					scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: ''
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '%'
                        },
                    }]
                }

				}
			});
			

	};

document.getElementById('overall').addEventListener('click', function() {
            

             
var chartEl = document.getElementById("chart");
			window.myLine = new Chart(chartEl, {
				type: 'line',
				data: lineChartData,
				options: {
					title:{
						display:true,
						text:'Progress chart'
					},
					tooltips: {
						enabled: true,
						mode: 'index',
						position: 'nearest',
						custom: customTooltips
					},
					scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Week'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Marks'
                        },
                    }]
                }
				}
			});
			        
        });


		
	
	//line chart
		Chart.defaults.global.pointHitDetectionRadius = 1;

		var customTooltips = function(tooltip) {
			// Tooltip Element
			var tooltipEl = document.getElementById('chartjs-tooltip');

			if (!tooltipEl) {
				tooltipEl = document.createElement('div');
				tooltipEl.id = 'chartjs-tooltip';
				tooltipEl.innerHTML = "<table></table>"
				document.body.appendChild(tooltipEl);
			}

			// Hide if no tooltip
			if (tooltip.opacity === 0) {
				tooltipEl.style.opacity = 0;
				return;
			}

			// Set caret Position
			tooltipEl.classList.remove('above', 'below', 'no-transform');
			if (tooltip.yAlign) {
				tooltipEl.classList.add(tooltip.yAlign);
			} else {
				tooltipEl.classList.add('no-transform');
			}

			function getBody(bodyItem) {
				return bodyItem.lines;
			}

			// Set Text
			/*if (tooltip.body) {
				var titleLines = tooltip.title || [];
				var bodyLines = tooltip.body.map(getBody);

				var innerHtml = '<thead>';

				titleLines.forEach(function(title) {
					innerHtml += '<tr><th>' + title + '</th></tr>';
				});
				innerHtml += '</thead><tbody>';

				bodyLines.forEach(function(body, i) {
					
					var colors = tooltip.labelColors[i];
					var style = 'background:' + colors.backgroundColor;
					style += '; border-color:' + colors.borderColor;
					style += '; border-width: 2px'; 
					var span = '<span class="chartjs-tooltip-key" style="' + style + '"></span>';
					innerHtml += '<tr><td>' + span +  body + '</td></tr>';
				});
				innerHtml += '</tbody>';

				var tableRoot = tooltipEl.querySelector('table');
				tableRoot.innerHTML = innerHtml;
			}

			var position = this._chart.canvas.getBoundingClientRect();

			// Display, position, and set styles for font
			tooltipEl.style.opacity = 1;
			tooltipEl.style.left = position.left + tooltip.caretX + 'px';
			tooltipEl.style.top = position.top + tooltip.caretY + 'px';
			tooltipEl.style.fontFamily = tooltip._fontFamily;
			tooltipEl.style.fontSize = tooltip.fontSize;
			tooltipEl.style.fontStyle = tooltip._fontStyle;
			tooltipEl.style.padding = tooltip.yPadding + 'px ' + tooltip.xPadding + 'px';*/
		};

		
		var lineChartData = {
			labels: <?php echo json_encode($darr); ?>,
			datasets: [{
				label: "Overall Progress",
				borderColor: window.chartColors.red,
				pointBackgroundColor: window.chartColors.red,
				fill: false,
				data: <?php echo json_encode($parr); ?> 
			}]
		};
		<?php 
		foreach($category as $cat)
		{
		$c=$cat['category_id'];
		?>
		var catid=<?php echo json_encode($c); ?>;
		document.getElementById(catid).addEventListener('click', function() {
           
			
    var lineChartDataCat = {
			labels: <?php echo json_encode($cdarr[$c]); ?>,
			datasets: [{
				label: <?php echo json_encode($lbls[$c-1]); ?>  ,
				borderColor: window.chartColors.blue,
				pointBackgroundColor: window.chartColors.blue,
				fill: false,
				data: <?php echo json_encode($array_val[$c]); ?> 
			}]
		};          
var chartEl = document.getElementById("chart");
			window.myLine = new Chart(chartEl, {
				type: 'line',
				data: lineChartDataCat,
				options: {
					title:{
						display:true,
						text:'Progress chart'
					},
					tooltips: {
						enabled: true,
						mode: 'index',
						position: 'nearest',
						custom: customTooltips
					}
				}
			});
			        
        });
		<?php
		}
		?>
		<?php
		$ws=[];
		for($i=1;$i<sizeof($d1)-1;$i++)
		{
		?>
		var wi=<?php echo json_encode($i);?>;
		var wid="Week"+wi;
	document.getElementById(wid).addEventListener('click', function() {
		
		
			
		var config1 = {
            type: 'line',
            data: {
                labels: <?php echo json_encode($week_lbl[$i]); ?>,
                datasets: [{
                    label: "My mark ",
                    borderColor: window.chartColors.red,
                    backgroundColor: window.chartColors.blue,
                    data: <?php echo json_encode($week_val[$i]); ?>,
                    fill: true,
                    steppedLine: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Weekwise Progress'
                },
                tooltips: {
                    mode: 'index',
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Week'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Marks'
                        },
                    }]
                }
            }
        };
	var ctx1 = document.getElementById("canvas1").getContext("2d");
            window.myLine = new Chart(ctx1, config1);
        
			
		});
		
	
        <?php
		}
		
		?>
		
       </script>   
      <!--main content end-->
	  
<div class="text-right">
	<div class="credits">
	
		<?PHP $this->load->view('template/footer'); ?>
	</div>
</div>
</section>

</body>
</html>
