<div class="form-group">

	<div class="col-md-12 col-sm-12 col-xs-12 item">
	  <label for="obs" class="control-label">Nova Observação:</label>
	  <textarea id="obs" class="form-control" name="obs" rows="5" style="width:100%;"></textarea>
	</div>

  	<div class="col-md-12 col-sm-12 col-xs-12 item">
  		@if(isset($favorecido) && count($favorecido->obs) > 0)
		<h4>Recentes Observações</h4>

		<!-- end of user messages -->
		<ul class="messages">
			@foreach ($favorecido->obs as $obs)
				<li style="list-style: none">
					<div class="message_date">
						<h3 class="date text-info">{{ substr( $obs->created_at, 8, 2) }}</h3>
						<p class="month">{{ substr( $obs->created_at, 5, 2) }}</p>
					</div>
					<div class="message_wrapper"  style="margin-left: 0">
						<blockquote class="message">{{$obs->obs}}</blockquote>
						<br>
						<p class="url">
						  <span class="fs1 text-info" aria-hidden="true" data-icon=""></span>
						  <a href="{{url('favorecido/deleteobs/'.$obs->id)}}"><i class="glyphicon glyphicon-remove"></i> </a>
						  <a href="#"><i class="fa fa-paperclip"></i>  {{$obs->usuario}}</a>
						</p>
					</div>
				</li>
			@endforeach
		</ul>
		<!-- end of user messages -->
		@endif

	</div>
  
</div>