<script>
  $(document).ready(function () {
     $('#sub').addClass('active');

     	$('#srch').keyup(function(){
  		var srchval = $('#srch').val();
  		var url     = "{{ route('SerachTicket') }}";
  		   $.ajax({'url':url, type:'get', data:{search_val:srchval}, success:function(result){
  			 $('#srch_div').html(result);
  		          }});
  		 
  		 });

     	$('#filter').change(function(){
     		var filter_val = $('#filter').val();
     		var url     = "{{ route('FilterTicket') }}";

  		   $.ajax({'url':url, type:'get', data:{filter_val:filter_val}, success:function(result){
  			           $('#srch_div').html(result);
  		          }});
	     		
     	});
   });

  
   setInterval(function(){ $("#refresh").load(location.href+" #refresh>*","");}, 1000);
</script>
 
<div class="left">
 
    <div class="left_inr" id="user-menu">
	 
		  <div class="top">
			<div class="chat-search-area">
				<input type="text" name="srch" id="srch" placeholder="Search">
			</div>
				<select class="chat-user-select" name="filter" id="filter">
					<option value="">All</option>
					<option value="Unread">Unread</option>
					<option value="0">Open</option>
					<option value="2">Closed</option>
				</select>
			
		</div><!--end top-->


			<div id="srch_div" class="left-middle-content">
				<div id="refresh">
				<?php $i=0; ?>
				<ul class="people">
				@foreach($ticketlists AS $ticketlist)
				@if($ticket_id == $ticketlist->id)
				<li id="sub" class="person active">
				@else
				<li class="person">
				@endif
					<a href="{{ route('ViewTicket',['ticket_id' => base64_encode($ticketlist->id), 'val' =>'val']) }}">
                       <div class="ticket-subj-list-info">
						<span class="numb">{{$newmsg[$i]}}</span>
						<div class="ticket-icon"></div>

                        <div class="text-info">
						<h3 class="ticket-subj-name">{{$ticketlist->subject}}</h3>
						<h4 class="ticket-subj-smInfo">
						{{$ticketlist->issues}}
					    </h4>
				      </div>
				      
                    </div>
				</a>
				</li>
				<?php $i++; ?>
				@endforeach
			</ul>
			</div>
			</div>
			
	
 
	</div>

</div>
 
