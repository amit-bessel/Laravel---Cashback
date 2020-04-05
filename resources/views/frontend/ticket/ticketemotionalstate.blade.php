@extends('../layout/frontend_template')
@section('content')


<script>
	$(document).ready(function(){
		$("#emotional_state").change(function(){
			var es_val = $("#emotional_state").val();
			var url    = "{{route('TicketEmotionalState')}}";

			$.ajax({url:url, type:'get', data:{esVal:es_val}, success:function(result){
					if (result == 1) {
						window.location = "{{route('AddTicket')}}";
					}
			}});
		});
	});
</script>

    <!-- maincontent -->
    <section class="maincontent">
        <div class="container">
            <hr class="special-divider">
            <!-- common-headerblock -->
        <div class="common-headerblock text-center">
                <h4 class="text-uppercase">Add Emotion For Tickets</h4>
        </div>
            <!--\\ common-headerblock -->
            <div class="row mt20">

              @include('frontend.includes.left')
              
                <div class="col-sm-8 col-md-9 right-userpanel">
               
                    <div class="user-formpanel ">
                    
                            <div class="form-group">
                              <div class="row">
                                  
                                  <div class="col-sm-12">
                                      <label>Emotional State:</label>
                                      <select name="emotional_state" class="form-control" id="emotional_state">
                                      	<option>Select</option>
                                        @foreach($ticketemotions AS $ticketemotion)
                                        <option value="{{$ticketemotion->id}}">{{$ticketemotion->emotional_state}}</option>
                                        @endforeach
                                      </select>
                                      
                                  </div>
                            
                              </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- maincontent -->

@stop