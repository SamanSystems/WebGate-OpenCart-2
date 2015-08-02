<h2><?php echo $text_instruction; ?></h2>
<p><b><?php echo $text_description; ?></b></p>
<div class="well well-sm">
  <p><?php echo $bank; ?></p>
  <p><?php echo $text_payment; ?></p>
</div>
<div class="buttons">
  <div class="right"><a id="button-confirm" class="btn btn-primary"><span><?php echo $button_confirm; ?></span></a></div>
</div>

<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=payment/zarinpal/confirm',
		dataType: 'json',
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);

			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);

				$('#button-confirm').attr('disabled', false);

			}

			$('.attention').remove();

			if (json['success']) {
				$('#payment').before('<div class="success"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_ersal; ?></div>');
				location = json['success'];
			}



		}
	});
});
//--></script>