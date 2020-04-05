@extends('../layout/frontend_template')
@section('content')

  
<script>
     $(document).ready(function(){
         
         $("#reply").click(function(){
          $("#myDiv").load(location.href+" #myDiv>*","");
           var formdata = new FormData($("#formId")[0]);
        
           var url        = "{{route('UserAddReplyTicket')}}";

           //alert(url);  
           $.ajax({url:url, 
                    type:'POST', 
                    data: formdata,
                    headers: {
                    'X-CSRF-Token':"{{ csrf_token() }}"
                    },
                    contentType:false,
                    processData: false,
                    cache:false,
                    beforeSend: function() {    
                        $(".chat-loader-holder").show();
                    }, 
                    success:function(result){

                    setTimeout(function(){  
                    $(".chat-loader-holder").hide();  
                    $("#replymsg").append(result);
                    $("#reply_val").val('');
                    $("#file").val('');
                    $(".chat-outer").animate({ scrollTop: $(".chat").height() }, "slow");
                    //return false;
                  },2500);
                    
               }});
        })

        $("#Yes").click(function(){
            var url       = "{{route('CloseTicket')}}";
            var ticket_id = "{{$ticket_id}}";
            var val       = $("#Yes").val();
             
            $.ajax({url:url, type:'get', data:{val:val,tid:ticket_id}, success:function(result){
                 if (result == 1) {
                    location.reload(true);
                 }
              }});
        });

        $("#No").click(function(){
            var url       = "{{route('CloseTicket')}}";
            var ticket_id = "{{$ticket_id}}";
            var val       = $("#No").val();

            $.ajax({url:url, type:'get', data:{val:val,tid:ticket_id}, success:function(result){
                 if (result == 1) {
                    location.reload(true);
                 }
                  
            }});
        });

        setInterval(function(){$("#myDiv").load(location.href+" #myDiv>*","");}, 100000);
    });
</script>

    <div class="comn-main-wrap-inr">
          <div class="main-heading">
          <h2>Support</h2>

          <a href="<?php echo url();?>/ticket/add" class="btn btn-create"><i class="la la-plus"></i> Create Ticket</a>
          </div>
        <div class="custom-card">
            
            <div class="chatbox-wrapper">
              <div class="container">

                @if(Session::has('success'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success') }}</p>
                @endif
              
               @include('frontend.includes.ticketlist')

                @if(isset($val) and $val == 'val')
                   <div class="right" style="display: block">
                @else
                <div class="right">
                @endif
                   
                     	  	<div class="top d-flex align-items-center justify-content-between">

                            <div class="chat-close-btn"><a href="{{ route('ViewTicket',['val' =>'']) }}"><i class="fas fa-times"></i></a></div>

                             <div class="top-ticket-right-info">
                                <h2 class="subj-name">{{$getTicket->subject}}</h1>
                                <h4 class="issue-type"> {{$getTicket->issues}} </h4>
                              </div>
                              <span class="top-ticket-create-date">Created on: {{date('d-M-Y', strtotime($getTicket->created_at))}}</span>
                </div>

                 <div class="chat-outer">
                 <div class="chat" id="myDiv">

                    <div class="bubble-you">
                      <div class="bubble-content">
                      <p><?php echo nl2br($getTicket->description) ?></p>
                       <div class="chat-time-show">{{date('h:i a', strtotime($getTicket->created_at))}}</div>
                      </div>
                    </div>
                       <?php $datear=array();?>
                    
                        @foreach($replydetails as $replymsg)
                        <?php 
                                   $current    = strtotime(date("Y-m-d"));
                                  $created_at_ar=explode(" ", $replymsg->created_at);
                                  $created_at=$created_at_ar[0];
                                 
                                  $date       = strtotime($created_at);

                                  $datediff   = $date - $current;

                                  $difference = floor($datediff/(60*60*24));

                                  
                                   



                                  if($difference==0){
                        ?>
                          <div class="day-show">
                            

                           <?php
                           if (in_array($created_at, $datear))
                            {
                                //echo "Match found";
                            }
                            else
                            {
                                //echo "Match not found";
                                 
                                 array_push($datear,$created_at);
                                 echo "<p style='color:#31c3e4'><b>Today</b></p>";
                                 
                            }
                           ?>                       
                          </div> 
                        <?php
                                  }
                                  else if($difference==-1){
                        ?>
                            <div class="day-show">

                            <?php
                            if (in_array($created_at, $datear))
                            {
                                //echo "Match found"; 
                            }
                            else
                            {
                                //echo "Match not found";

                                 array_push($datear,$created_at);
                                 //echo  date('F d,Y', strtotime($replymsg->created_at));
                                 echo "<p style='color:#31c3e4'><b>Yesterday</b></p>";

                            }
                            ?> 

                                                                
                          </div>
                        <?php
                                  }
                                  else{

                                          if (in_array($created_at, $datear))
                                          {
                                              //echo "Match found";
                                          }
                                          else
                                          {
                                              //echo "Match not found";

                                               array_push($datear,$created_at);
                                               echo "<div class='day-show'>" .date('F d,Y', strtotime($replymsg->created_at))."</div>";
                                              // echo "Yesterday";

                                          }

                        ?>


                                  <?php

                                }
                                ?>

                              

                                



                         
                            <div class="bubble-me">
                            @if($replymsg['replybyuser'] == '' and $replymsg['replytoadmin'] == '')

                            <div class="bubble-content">
                              <h3 class="bubble-content-heading">Checkout Saver Support</h3>
                            <p><?php echo nl2br($replymsg->msg);?></p>

                            @if(!empty($replymsg->attachment))

                               <?php 
                                   $extension   = explode('.', $replymsg->attachment);
                                   $length      = count($extension);
                                   $ext         = $extension[$length-1]; 
                                   $extensionar = array('docs','odt','ods');
                                ?>
                                 
                                @if(in_array($ext, $extensionar))
                                <div class="attach-file-sec">
                                  <a href="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" download="download">{{$replymsg->attachment}}</a>
                                </div>
                                @else
                                  <img src="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" height="60" width="80">
                                @endif
                            @endif

                            <div class="chat-time-show">{{date('h:i a', strtotime($replymsg->created_at))}}</div>

                         
                             </div>
                            @endif
                               </div>



                            <div class="bubble-you">
                            @if($replymsg['replybyadmin'] == '' and $replymsg['replytouser'] == '')

                           <div class="bubble-content">
                            <h3 class="bubble-content-heading">You</h3>
                           <p><?php echo nl2br($replymsg->msg);?></p>
                            
                           @if(!empty($replymsg->attachment))
                                <?php 
                                    $extension   = explode('.', $replymsg->attachment);
                                    $length      = count($extension);
                                    $ext         = $extension[$length-1]; 
                                    $extensionar = array('docs','odt','ods');
                                ?>

                                @if(in_array($ext, $extensionar))
                                    <div class="attach-file-sec">
                                     <a href="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" download="download">{{$replymsg->attachment}}</a>
                                   </div>
                                @else
                                     <img src="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" height="60" width="80">
                                @endif
                            @endif
                            

                            <div class="chat-time-show">{{date('h:i a', strtotime($replymsg->created_at))}}</div>

                             </div>
                            @endif
                            </div> 
                        @endforeach
                       
                      <div class="bubble_you">
                       <div class="" id="replymsg"></div>
                      </div>
                    
                  </div><!--end chat-->
                </div><!--end chat-outer-->

                   <div class="write">
                    <div class="write-inr">

                    <form id="formId" action="" method="post" enctype="multipart/form-data">
                      <input name="_token" value="{{csrf_token()}}" type="hidden">
                      <input type="hidden" name="user_id" value="{{$getTicket->siteusers_id}}">
                      <input type="hidden" name="ticket_id" value="{{$getTicket->id}}">

                      <div class="write-input-wrap">
                      @if($getTicket->status == 1 AND $getTicket->id == $ticket_id)


                     
                     
                       <textarea  name="reply_val" id="reply_val" placeholder="" class="form-control" disabled="disabled"> </textarea>
                       <span class="attach">
                       <input type="file" name="file" id="file" disabled="disabled">
                       </span>

                      @elseif($getTicket->status == 0 AND $getTicket->id == $ticket_id)
                        <textarea  name="reply_val" id="reply_val" placeholder="" class="form-control" valign='middle'> </textarea>
                        <span class="attach">
                       <input type="file" name="file" id="file">
                        </span>
                      @endif
                      <span class="chatonlyimage">Only image can be uploaded.</span>
                    </div>


                      @if($getTicket->status == 0 AND $getTicket->id == $ticket_id)
                      <div class="send-btn">
                      <input class="btn btn-solid-blue" name="submit" value="Send" type="button" id="reply" onclick="refresh()">  
                      </div>


                      @elseif($getTicket->status == 1 AND $getTicket->id == $ticket_id)

                      <div class="chat-alertBox-sec">
                         <div class="chat-alertBox-content d-flex align-items-center justify-content-end">
                            <div class="chat-alertBox">
                              Checkout Saver Support Team has solved this issue. Do you want to close this ticket?
                            </div>

                           <div class="chat-alert-btnBox"> 
                       <input class="btn btn-close" name="submit" value="Close" type="button" id="Yes"> 
                      <input class="btn btn-solid-blue" name="submit" value="Reopen" type="button" id="No">
                        </div>

                         </div>
                      </div>      





                      @else
                        
                        <div class="chat-alertBox-sec">
                         <div class="chat-alertBox-content d-flex align-items-center justify-content-center">
                            <div class="chat-alertBox">
                              <h4 class="red-alert-text">This issue has been closed</h4>
                            </div>
                         </div>
                        </div>

                      @endif
                      </form>  

                      <div class="chat-alertBox-sec chat-loader-holder" style="display: none;">
                         <div class="chat-alertBox-content d-flex align-items-center justify-content-end">
                            <div class="chat-alertBox">
                            <div class="cssload-jumping">
                              <span></span><span></span><span></span><span></span><span></span>
                            </div><!--loader-->
                            </div>
                         </div>
                      </div> 

                     </div><!--end write-inr-->
                    </div><!--end write-->


                         
                  </div><!--end right-->
                  
              </div><!--end container-->
            </div>
        </div>
    </div>
    <!-- maincontent -->

@stop