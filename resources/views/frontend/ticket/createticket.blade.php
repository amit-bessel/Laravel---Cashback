@extends('../layout/frontend_template')
@section('content')


        <div class="comn-main-wrap-inr">
            
     <div class="main-heading">
           <h2>Support</h2>
        </div>

           <div class="custom-card ticketCreate-card">

               <div class="custom-card-body">

       
              <div class="ticketCreate-form-wrap">

              <div class="ticketCreate-form-heading">
              <h2>Create a new support ticket</h2>
              </div>


              @if(Session::has('error'))
                  <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('error') }}</p>
                @endif
      
                @if(Session::has('success_message'))
                  <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('success_message') }}</p>
                @endif

                {!! Form::open(['url' => 'ticket/add','method'=>'POST', 'files'=>true,'class'=>'','id'=>'addticket']) !!}
                  <div class="comn-form-content">
                    


                          <div class="form-group">
                                      <label class="form-control-label">Issue type</label>
                                      <select name="Ticket[issuetype_id]" class="form-control">
                                        @foreach($ticketissues AS $ticketissue)
                                        <option value="{{$ticketissue->id}}">{{$ticketissue->issue_type}}</option>
                                        @endforeach
                                      </select>

                          </div> 

                          <div class="form-group">
                              <label class="form-control-label">Subject </label>
                               <span class="letter-count-numb" id="ticket_subj_count_numb">50</span>
                              <input type="text" class="form-control" name="Ticket[subject]" placeholder="Enter Subject Name" id="ticket_subj_field" maxlength="50">
                              <label class="error">{{ $errors->first('subject') }}</label>
                          </div>


<!--                           <div class="form-group">
                          
                                      <label class="form-control-label">Emotional State</label>
                                      <select name="Ticket[emotionstate_id]" class="form-control" id="emotional_state">
                                        <option>Select</option>
                                        @foreach($ticketemotions AS $ticketemotion)
                                        <option  data-imagesrc="<?php //echo url(); ?>/public/frontend/images/icons/excited-emoji.png" value="{{$ticketemotion->id}}" {{($ticketemotion->id==$esval)?'selected':''}}>
                                        {{$ticketemotion->emotional_state}}</option>
                                        @endforeach
                                      </select>
                                    
                            </div> -->



                            <div class="emotion-dropdown-holder form-group dropdown">

                               <label class="form-control-label">Emotional state</label>
                                <a class="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" href="javascript:;">
                                  <div class="emotion-dropdown-selected-value">Select</div>
                                </a>
                                <div id="useremotionalstate">
                                </div>
                            <div class="dropdown-menu emotion-dropdown-menu">
                                <ul>
                                <li>Select</li>
                                @foreach($ticketemotions AS $ticketemotion)
                                <li data-value="<img src='<?php echo url(); ?>/public/frontend/images/icons/emoji-{{$ticketemotion->id}}.png'> {{$ticketemotion->emotional_state}}" onclick="changeemotionalstate('{{$ticketemotion->id}}')"><img src="<?php echo url(); ?>/public/frontend/images/icons/emoji-{{$ticketemotion->id}}.png" > {{$ticketemotion->emotional_state}}</li>
                                @endforeach
                                </ul>
                                </div>

                            </div>


                          <div class="form-group">
                              <label class="form-control-label">Description</label>
                              <span class="letter-count-numb" id="ticket_dsc_count_numb">500</span>
                              <textarea class="form-control" id="ticket_dsc_field" name="Ticket[description]" maxlength="500" placeholder="How Can We Help?" maxlength="50"></textarea>
                               <label class="error">{{ $errors->first('description') }}</label>
                          </div>
                          
                       <!--    <div class="form-group">
                               
                              <label>Attachment:</label>
                              <input type="file" class="form-control" name="attachment">
                              
                          </div> -->

                           <div class="btn-holder text-center">
                               <input class="btn btn-solid-blue" name="submit" value="submit" type="submit"> 
                          </div>
                  </div><!--end comn-form-content-->
                  {!! Form::close() !!}

                </div><!--end ticketCreate-form-wrap-->
              </div><!--end custom-card-body-->
            </div>
        </div> <!--end  comn-main-wrap-inr -->

   <script type="text/javascript">
     
     function changeemotionalstate(id){
      $("#useremotionalstate").html("<input type='hidden' name='emotionalstate' value='"+id+"'/>")
     }
   </script>

@stop