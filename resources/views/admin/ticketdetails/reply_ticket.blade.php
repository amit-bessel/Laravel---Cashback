@extends('admin/layout/admin_template')
 
@section('content')
 
<script>
      $(document).ready(function(){
         $("#reply").click(function(){
           var formdata = new FormData($("#formId")[0]);
        
           var url        = "{{route('AddReplyTicket')}}";  
           $.ajax({url:url, 
                    type:'POST', 
                    data: formdata,
                    contentType:false,
                    processData: false,
                    cache:false, 
                    success:function(result){
                    $("#replymsg").append(result);
                    $("#reply_val").val('');
                    $("#file").val('');
                    $(".chat-outer").animate({ scrollTop: $(".chat").height() }, "slow");
                  //alert(result);
               }});
      });

        setInterval(function(){$("#myDiv").load(location.href+" #myDiv>*","")}, 1000);
 });
</script>
 

<div class="chatbox-wrapper">
    <div class="container">
      
       <div class="right">

                
                 <div class="top d-flex align-items-center justify-content-between">
                             <div class="top-ticket-right-info">
                                <h2 class="prs-name"><?php if(!empty($ticketdetail->fname)){ echo $ticketdetail->fname.' '.$ticketdetail->lname;}?></h2>
                                <h2 class="subj-name">Subject: <?php if(!empty($ticketdetail->subject)){ echo $ticketdetail->subject;}?></h2>
                                <h4 class="issue-type">Emotional State: <?php if(!empty($ticketdetail->emotionstate)){ echo $ticketdetail->emotionstate;}?></h4>
                                <h4 class="issue-type">Issues Type: <?php if(!empty($ticketdetail->issues)){ echo $ticketdetail->issues; }?> </h4>
                              </div>
                              
                 </div>



                <div class="chat-outer">       
                    <div class="chat" id="myDiv">

                        <div class="bubble-you">

                        <div class="bubble-content">
    
                        <p><?php if(!empty($ticketdetail->description)){ echo nl2br($ticketdetail->description); } ?></p>

                        @if(!empty($ticketdetail->attachment))
                              <?php 
                              $extension   = explode('.', $ticketdetail->attachment);
                              $length      = count($extension);
                              $ext         = $extension[$length-1]; 
                              $extensionar = array('docs','odt','ods');
                              ?>
                              @if(in_array($ext, $extensionar))
                              <a href="{{ URL::asset('public/uploads/attachments/'.$ticketdetail->attachment) }}" download="download">{{$ticketdetail->attachment}}</a>
                              @else 
                              <img src="{{ URL::asset('public/uploads/attachments/'.$ticketdetail->attachment) }}" height="60" width="80">
                              @endif
                        @endif

                      

                      <div class="chat-time-show"><?php if(!empty($ticketdetail->created_at)) { echo date('h:i a', strtotime($ticketdetail->created_at));}?></div>
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
                                 echo "<p>Today</p>";
                                 
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
                                 echo "<p>Yesterday</p>";

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
                                               echo "<div class='day-show'><p>" .date('F d,Y', strtotime($replymsg->created_at))."</p> </div>";
                                              // echo "Yesterday";

                                          }

                        ?>


                                  <?php

                                }
                                ?>


                            @if($replymsg['replybyuser'] == '' and $replymsg['replytoadmin'] == '')

                                 <div class="bubble-you">
                                 <div class="bubble-content">
                                  <h3 class="bubble-content-heading">You</h3>

                                  <p><?php echo nl2br($replymsg->msg) ?></p>


                                  @if(!empty($replymsg->attachment))
                                  <?php 
                                  $extension   = explode('.', $replymsg->attachment);
                                  $length      = count($extension);
                                  $ext         = $extension[$length-1]; 
                                  $extensionar = array('docs','odt','ods');
                                  ?>
                                  @if(in_array($ext, $extensionar))
                                  <a href="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" download="download">{{$replymsg->attachment}}</a>
                                  @else 
                                  <img src="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" height="60" width="80">
                                  @endif
                                  @endif
                                  

                                  <div class="chat-time-show">{{date('h:i a', strtotime($replymsg->created_at))}}</div>

                                  </div>
                                  </div>
                            @endif


                                 
                            @if($replymsg['replybyadmin'] == '' and $replymsg['replytouser'] == '')
                             
                             <div class="bubble-you">
                                 <div class="bubble-content">

                               
                                <p><?php echo nl2br($replymsg->msg) ?></p>


                                @if(!empty($replymsg->attachment))
                                <?php 
                                 $extension   = explode('.', $replymsg->attachment);
                                 $length      = count($extension);
                                 $ext         = $extension[$length-1]; 
                                 $extensionar = array('docs','odt','ods');
                                ?>
                                @if(in_array($ext, $extensionar))
                                   <a href="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" download="download">{{$replymsg->attachment}}</a>
                                @else 
                                   <img src="{{ URL::asset('public/uploads/attachments/'.$replymsg->attachment) }}" height="60" width="80">
                                @endif
                                @endif
                                
                            <div class="chat-time-show">{{date('h:i a', strtotime($replymsg->created_at))}}</div>
                           
                           </div>
                           </div>

                            @endif

                       
                        @endforeach
                         
                        
                        <h4><span style="background-color: yellow" id="replymsg"></span></h4> 

                    </div>
                    </div><!--end chat-outer-->
              
                   <div class="write">
                    <div class="write-inr">

               <form id="formId" action="" method="post" enctype="multipart/form-data">
                  <input name="_token" value="{{csrf_token()}}" type="hidden">
                  <input type="hidden" name="user_id" value="<?php if(!empty($ticketdetail->siteusers_id)){ echo $ticketdetail->siteusers_id; }?>">
                      <input type="hidden" name="ticket_id" value="<?php if(!empty($ticketdetail->id)) { echo $ticketdetail->id;}?>">


                     <div class="write-input-wrap">
                      @if(!empty($ticketdetail->id))
                       @if($ticketdetail->status == 1 AND $ticketdetail->id == $ticket_id)

                       

                       <textarea  name="reply_vals" id="reply_vals" placeholder="Reply Message" class="form-control" disabled="disabled"> </textarea>
                       <span class="attach">
                       <input type="file" name="file" id="files" disabled="disabled">
                       </span>
                      

                      @elseif($ticketdetail->status == 0 AND $ticketdetail->id == $ticket_id)
                        <textarea  name="reply_val" id="reply_val" placeholder="Reply Message" class="form-control"> </textarea>
                        <span class="attach">
                       <input type="file" name="file" id="file">
                       </span>

                      @endif
                      </div>
                      
                      @if($ticketdetail->status == 0 AND $ticketdetail->id == $ticket_id)
                      <div class="send-btn">
                      <input class=" btn btn-blue" name="submit" value="Send" type="button" id="reply" onclick="refresh()">  
                      </div>
                      @else
                        <h4 style="color: red" align="center">This issue has been closed</h4>
                      @endif
                      @endif
                      
               </form>

                </div><!--end write-inr-->
              </div><!--end write-->


             
          </div>
                  
    </div>               
                                

  </div>

@endsection
