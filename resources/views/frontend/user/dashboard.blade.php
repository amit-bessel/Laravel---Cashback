@extends('../layout/frontend_template')
@section('content')

<div class="comn-main-wrap-inr">

<div class="row">
	<div class="col-xl-9 col-lg-8 col-md-8 dsboard-left-col">

      <div class="row">
       <div class="col-lg-4">
<?php if(number_format($userprofileheadinfo->wallettotalamount,2) > 0){ ?>
		<div class="dsboard-card dsboard-actBlc-card ">
	      <div class="dsboard-card-body">

	      <div class="actBlc-card-content">
	      	<div class="icon-holder">
	      	<img src="<?php echo url(); ?>/public/frontend/images/icons/wallet.svg">
	      	</div>
            <h3>Account Balance</h3>
            <div class="text-center price-text">$<?php echo number_format($userprofileheadinfo->wallettotalamount,2);?></div>
            <div class="btn-holder">
            	<a href="" class="btn btn-solid-blue">Withdraw</a>
            </div>
	      </div><!--end actBlc-card-content-->         

	     </div>
	    </div><!--end dsboard-card-->
	    <?php }
	    else { ?>
	    	<div class="dsboard-card dsboard-actBlc-card no-data">
	    		<div class="dsboard-card-body">
	      			<div class="actBlc-card-content">
	      				<div class="icon-holder">
	      				</div>
	      			</div>
	      		</div>
	    	</div>
	    <?php } ?>

        </div><!--end col-->

        <div class="col-lg-8">

		<div class="dsboard-card dsboard-totalEarned-card ">
	      <div class="dsboard-card-body"> 
	      <div class="dsboard-card-heading dsboard-totalEarned-card-heading">
	      	<h2>Total Earned</h2>
	      	<span class="blnc-text">$<?php echo number_format($userprofileheadinfo->wallettotalamount,2);?></span>
	      </div>   
          
          <div class="totalEarned-card-content">

          	<canvas id="totalEarned_chart"></canvas>

           </div><!--end totalEarned-card-content-->

	     </div>
	    </div><!--end dsboard-card-->
	    
        </div><!--end col-->
       </div><!--end row-->

      <div class="row">
        <div class="col-lg-8">

		<div class="dsboard-card dsboard-monthlyIncome-card">
	      <div class="dsboard-card-body"> 
	      <div class="dsboard-card-heading dsboard-monthlyIncome-card-heading">
	      	<h2>Monthly Income</h2>
	      </div>   
          
          <div class="monthlyIncome-card-content">

          	<canvas id="monthlyIncome_chart"></canvas>

           </div><!--end totalEarned-card-content-->

	     </div>
	    </div><!--end dsboard-card-->
	    
        </div><!--end col-->

       <div class="col-lg-4">
		<div class="dsboard-card incomeSrc-dsboard-card">
	      <!-- <div class="dsboard-card-body"> -->

	      <div class="incomeSrc-card-content d-flex align-items-center justify-content-center">
           <canvas id="incomeSrc_chart" width="500" height="500"></canvas>
	      </div><!--end actBlc-card-content-->         

	     <!-- </div> -->
	    </div><!--end dsboard-card-->
        </div><!--end col-->

       </div><!--end row-->


     </div><!--end col-->

     <div class="col-xl-3 col-lg-4 col-md-4 dsboard-right-col">
		<div class="dsboard-card dsboard-referral-card">
	      <div class="dsboard-card-body">

	      <div class="dsboard-card-heading dsboard-referral-card-heading">
	      	<h2>Referral Commision</h2>
	      	<span class="blnc-text">$<?php if($total_Commission['amount'] == '') echo 0; else echo$total_Commission['amount']; ?></span>
	      </div>

<div class="referral-card-content-wrap">
	<div class="referral-card-content">
	<?php 
	if(count($registerinvite) > 0){
		foreach ($registerinvite as $key => $value) {
	?>
	<div class="referral-content-block">
           <div class="referral-content-dsc">
				<?php if(!empty($value['userreferlink1']['profileimage'])){?>
				<img src="<?php echo url(); ?>/public/backend/profileimage/<?php echo $value['userreferlink1']['profileimage'];?>">
				<?php } 
				else {?>
				<img src="<?php echo url(); ?>/public/frontend/images/demo-prfl-img.jpg"><?php }?>
				<div class="text-info">
					<h3 class="name"><?php echo $value['userreferlink1']['firstname'].' '.$value['userreferlink1']['lastname'] ?> </h3>
					<h4 class="price">$0.0</h4>
				</div>
		 	</div>
		</div>
		
	<?php
		}
	}
	else{
	 ?>
		<div class="referral-content-block noData">
            <img src="<?php echo url('');?>/public/frontend/images/empty-data-img2.jpg">
		</div><!--end referral-content-block-->
	<?php 
		}
	 ?>
		

	</div><!--end referral-card-content-->
<?php if(count($registerinvite) > 0){ ?>
<!-- <div class="bottom-content text-right">
	<a href="#" class="view-more-link">view More <i class="fas fa-angle-right"></i></a>
</div> -->
<?php } ?>

</div><!--end referral-card-content-wrap-->

	      	</div>
	    </div>
     </div><!--end col-->

 </div><!--end row-->
</div><!--end comn-main-wrap-inr-->


<script type="text/javascript">
	earningTotal();
	var dates = new Array();
	var amounet = new Array();
	var prev_month_date = new Array();
	var prev_month_earning = new Array();
	var prev_month_save = new Array();
	var total_save = 0;
	var total_commission = 0;

	function earningTotal(){
            $.ajax({              
              type : 'post',
              url :  '<?php echo url().'/user/user-totalearn'; ?>',
              data : { _token: "{{ csrf_token() }}"},
              success : function(response){
                var data = JSON.parse(response);
                var amounts = data['total_earn']['amt'];
                var responseDates = data['total_earn']['dat'];
                var responseDatesMonth = data['prev_month_earn']['dat'];
                var responseMonthEarn = data['prev_month_earn']['amt'];
                var responseMonthSave = data['prev_month_save']['amt'];
                total_save = data['total_save'];
                total_commission = data['total_commission'];
                amount = amounts.toString();
                for(var i=0; i<responseDates.length; i++){
                	dates.push(new Date(responseDates[i]));
                	amounet.push(amounts[i]);
                }
                for(var i= 0; i<responseDatesMonth.length; i++){
                	prev_month_date.push(new Date(responseDatesMonth[i]));
                	prev_month_earning.push(responseMonthEarn[i]);
                	prev_month_save.push(responseMonthSave[i]);
                }
                setTimeout(function(){
                    totalearnchart();
                    monthlyearnchart();
                    incomeSource();
                }, 1000);
               
               
                
              }
            });
      }

     

var timeFormat = 'DDD HH:mm:ss';

		function newDate(days) {
			// console.log(moment().add(days, 'd').toDate());
			return moment().add(days, 'd').toDate();
		}

		function newDateString(days) {
			return moment().add(days, 'd').format(timeFormat);
		}

		function newTimestamp(days) {
			return moment().add(days, 'd').unix();
		}
function totalearnchart(){
// console.log(newDate(0));
var ctx = document.getElementById("totalEarned_chart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: {

        labels: dates,
        datasets: [{
            data: amounet,
                       backgroundColor: 'rgba(0, 0, 0, 0.0)',
                        borderColor: '#31c3e4',
                        borderWidth: 4,
                        pointBorderWidth: 8,
                        pointRadius: 5,
                        pointBackgroundColor:'rgba(27, 170, 202,1)',
                        pointBorderColor: 'rgba(49, 195, 228,0.3)'
                        
        }]
    },
options: {
	layout: {
		padding: {
		left:20,
	}
	},

                title:{
                    text: "Chart.js Time Scale"
                },
                legend: {
            display: false,
                   },
                tooltips: {
						position: 'nearest',
						mode: 'index',
						intersect: false,
						yPadding: 10,
						xPadding: 10,
						caretSize: 8,
						backgroundColor: 'rgba(255, 255, 255, 1)',
						titleFontColor: window.chartColors.black,
						bodyFontColor: window.chartColors.black,
						borderColor: 'rgba(0,0,0,0.2)',
						borderWidth: 1
					},
				scales: {
					xAxes: [{
						type: "time",
						time: {
						displayFormats: {
                        'day': 'ddd DD'
                        },
							// round: 'day'
							tooltipFormat: 'll HH:mm'
						},
						scaleLabel: {
							display: false,
						}
					}, ],
					yAxes: [{
						scaleLabel: {
							display: false,
						}
					}]
				}
			}
});
}

//monthlyIncome_chart
function monthlyearnchart(){
var ctx2 = document.getElementById("monthlyIncome_chart");
var myChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        /*labels: [ // Date Objects
					newDate(0),
					newDate(1),
					newDate(2),
					newDate(3),
					newDate(4),
					newDate(5),
					newDate(6),
					newDate(7),
					newDate(8),
					newDate(9),
					newDate(10)
				],*/
				 labels: prev_month_date,
        datasets: [
        {

            label: 'Total Earned',
            data: prev_month_earning,
 
            
                       backgroundColor: '#6bdb54',
                        
        },
        {

            label: 'Total Saved',
            data: prev_month_save,
 
                       backgroundColor: '#6381e6',
                        
        }


        ]
    },
options: {
                title:{
                    text: "Chart.js Time Scale"
                },

                legend: {
            display: true,
            position: 'bottom',

            labels: {
            	boxWidth:6,
            	lineWidth: 0,
            	fontStyle:'400',
            	 fontSize:16,
                 fontColor:'#666668',
                 padding:40,
                 usePointStyle:true
                    }

                   },

                tooltips: {
						position: 'nearest',
						mode: 'index',
						intersect: false,
						yPadding: 10,
						xPadding: 10,
						caretSize: 8,
						backgroundColor: 'rgba(255, 255, 255, 1)',
						titleFontColor: window.chartColors.black,
						bodyFontColor: window.chartColors.black,
						borderColor: 'rgba(0,0,0,0.2)',
						borderWidth: 1
					},

                     responsive: true,

					scales: {
					xAxes: [{
              
						type: "time",
						time: {
						displayFormats: {
                        'day': 'ddd'
                        },
							tooltipFormat: 'll HH:mm'
						},
                     offset:true,
					stacked: true,
					barPercentage:0.5
					}],

					yAxes: [{
					stacked: true
					}]

					}
			}
});
}
// Income Source
function incomeSource(){
var ctx3 = document.getElementById("incomeSrc_chart");
console.log(total_save);
if(total_save!=null && total_commission !=null){
var myChart = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: [ 'Commission' , 'Cash Back'],
        datasets: [{
            data: [total_save, total_commission],
                       backgroundColor: ['#6bdb54','#484eff'],
                        borderColor:'rgba(255,255,255,0.1)',
                        borderWidth: 15,
                        
        }]
    },
options: {
                title:{
                    text: ""
                },
                responsive: true,
                tooltips: {

						yPadding: 10,
						xPadding: 10,
						caretSize: 8,
						backgroundColor: 'rgba(255, 255, 255, 1)',
						titleFontColor: window.chartColors.black,
						bodyFontColor: window.chartColors.black,
						borderColor: 'rgba(0,0,0,0.2)',
						borderWidth: 1
					},
                legend: {
            display: true,
            position: 'bottom',
            
            labels: {
            	lineWidth: 0,
            	borderWidth: 1,
            	fontStyle:'400',
            	 fontSize:16,
                 fontColor:'#000000',
                 padding:15,
                 usePointStyle:true
                    }

                   }
			}
});
}
else
{
	$(".incomeSrc-card-content").html("No data found");
}
}

</script>

@stop


