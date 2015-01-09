@extends('AdminTemplate.template')

@section('content')
	<script type="text/javascript">
		function notify(text, status) {
			var msg = $('<div style="display: none" class="alert alert-'+status+'">\
					<p>'+text+'</p>\
				</div>'
			);
			$('#notify').append(msg);
			msg.show("slow");
			setTimeout(function() {
				msg.hide("fast", function() {
					msg.remove();
				});
			}, 3000);
		}
		function delete_click(id) {
			$('#delete-'+id).hide();
			$.post( "/admin/deletePage", {'id_album' : id} )
			.success(function( data ) {
				var response = JSON.parse(data);
				notify('Альбом удален. Фотографий удалено:'+response.deletedPhotos, "success");
				$('#delete-'+id).parent().parent().hide("slow", function() {
					this.remove();
				});
			})
			.error(function() {
				notify("Ошибка удаления.", "danger");
				$('#delete-'+id).show("slow");
			});
		}
	</script>
	<div class="container altern">
		<div class="row">
			<h2>Удаление альбомов</h2>
			@if(!count($all_albums))
				<div class="bs-callout bs-callout-danger">
					<h4>Нет ни одного альбома!</h4>
					<p>Грусть, печаль...</p>
				</div>
			@else
				<div id="notify"></div>
				<table class="table">
					<thead>
						<tr>
							<th class="row-name-album">Имя</th>
							<th class="row-count-photo">Фотографий</th>
							<th class="row-delete-button">Удаление</th>
						</tr>
					</thead>
					<tbody id="upload-rows">
						@foreach($all_albums as $albom)
							<tr>
								<td>{{ $albom['name'] }}</td>
								<td>{{ $albom['count'] }}</td>
								<td><a href="javascript:" id="delete-{{ $albom['id'] }}" onclick="delete_click({{ $albom['id'] }})"><span class="glyphicon glyphicon-floppy-remove"></span></a></td>
							</tr>
						@endforeach
					</tbody>
				</table>
			@endif
		</div>
	</div>
	<div class="container simple">
		<div class="row">
			<h2>Удаление фотографий</h2>
			@if(!count($albums_with_photos))
				<div class="bs-callout bs-callout-danger">
					<h4>Нет ни одной фотографии!</h4>
					<p>Грусть, печаль...</p>
				</div>
			@else
				
			@endif
		</div>
	</div>
@stop