               
			   @if(!empty($ticketlists))
               <?php $i=0; ?>
            	<ul class="people">
				@foreach($ticketlists AS $ticketlist)
				 <li class="person">

				    <a href="{{ route('ViewTicket',['ticket_id' => base64_encode($ticketlist->id)]) }}">
                      <div class="ticket-subj-list-info">
                      	<span class="numb">{{$newmsg[$i]}}</span>
                      		<div class="ticket-icon"></div>

                      		<div class="text-info">
				    	<h3 class="ticket-subj-name">{{$ticketlist->subject}}</h3>
				    		<h4 class="ticket-subj-smInfo">{{$ticketlist->issues}}</h4>
                         </div>
				    	
                     </div>
				    </a>

				</li>
				<?php $i++; ?>
				@endforeach
			   </ul>
			   @else
			       <div class="noData-text">No result found</div>
			   @endif