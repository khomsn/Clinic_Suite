AM = {}; // Root name space

AM.forms = (function(){

	var init = function(){
		$(':checkbox.selectall').on('click', function(){
			$(':checkbox[name="' + $(this).data('checkbox-name') + '"]').prop("checked", $(this).prop("checked"));
            $(':checkbox[class="' + $(this).data('checkbox-class') + '"]').prop("checked", $(this).prop("checked"));
		});

		$(':checkbox.selectme').on('click', function(){
			var _selectall = $(this).prop("checked");
			if ( _selectall ) {
				$( ':checkbox[name="' + $(this).attr('name') + '"]' ).each(function(i){
					_selectall = $(this).prop("checked");
					return _selectall;
				});
				$( ':checkbox[class="' + $(this).attr('class') + '"]' ).each(function(i){
					_selectall = $(this).prop("checked");
					return _selectall;
				});
			}
			$(':checkbox[name="' + $(this).data('select-all') + '"]').prop("checked", _selectall);
            $(':checkbox[class="' + $(this).data('select-all') + '"]').prop("checked", _selectall);
		});
	}

	$(document).ready(function(){
		init();
	});

})();
