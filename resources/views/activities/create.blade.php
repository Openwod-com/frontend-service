<div class="modal fade" id="activityModal" tabindex="-1">
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title h5" style="display: inline;">Create new workout</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<form id="activityForm" role="form" method="POST">
					@csrf
					<div class="form-group">
						<label for="gym" class="control-label">Gym:</label>
						<select name="gym" id="gym" class="form-control">
							@foreach($boxes as $box)
								<option value="{{ $box->id }}">{{ $box->name }}</option>
							@endforeach
						</select>
					</div>
					<div class="form-group">
						<label for="subject" class="control-label">Activity:</label>
						<input type="text" name="subject" class="form-control" id="subject" placeholder="Activity" required>
					</div>
					<div class="form-group">
						<label for="act-description" class="control-label">Description:</label>
						<textarea name="description" class="form-control" id="act-description" placeholder="Description" rows="5" required></textarea>
					</div>
					<div class="form-group">
						<label for="act-location" class="control-label">Location:</label>
						<input type="text" name="location" class="form-control" id="act-location" placeholder="Location" required>
					</div>
					<div class="form-group">
						<label for="coach" class="control-label">Coach:</label>
						<input type="text" name="coach" class="form-control" id="coach" placeholder="Coach" required>
					</div>
					<div class="form-group">
						<label for="startTime" class="control-label">Start time:</label>
						<input type="text" class="form-control" name="start" id="startTime" placeholder="yyyy-mm-dd --:--" required>
					</div>
					<div class="form-group">
						<label for="duration" class="control-label">Length:</label>
						<select name="duration" id="duration" class="form-control">
							<option value="PT15M">15 minuter</option>
							<option value="PT30M">30 minuter</option>
							<option value="PT45M">45 minuter</option>
							<option value="PT1H" selected>1 timme</option>
							<option value="PT1H15M">1 och 15 minuter</option>
							<option value="PT1H30M">1 och 30 minuter</option>
							<option value="PT1H45M">1 och 45 minuter</option>
							<option value="PT2H">2 timme</option>
						</select>
					</div>
					<div class="form-group">
						<label for="maximum_users" class="control-label">Maximum number of participants:</label>
						<input type="text" name="maximum_users" class="form-control" id="maximum_users" placeholder="Maximum number of participants" value="30" required>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Save">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					</div>
					<input type="hidden" id="activityId" name="activityId" value="">
				</form>
				<div id="create_activity_error" class="alert alert-danger" style="display: none;" role="alert">Failed to save workout.</div>
				<div id="create_activity_primary_alert" class="alert alert-primary" style="display: none;" role="alert">Saving workout...</div>
				<div id="create_activity_success" class="alert alert-success" style="display: none;" role="alert">The workout have been saved!</div>
			</div>
		</div>
	</div>
</div>

@section('scripts2')
<script>
	$(document).ready(function() {
		$('#activityModal').on('submit', function(e) {
			e.preventDefault();
			$('#activityModal button[type="submit"]').attr('disabled','disabled').css("cursor", "not-allowed");
			$('#create_activity_error').css('display','none');
			$("#create_activity_primary_alert").css("display", "block");
			let modal = $('#activityModal');
			$activityID = $("#activityId").val();

			if ($activityID) {
				$ajax_type = 'PUT';
				$ajax_url = '/activities/' + $activityID;
			}
			else {
				$ajax_type = 'POST';
				$ajax_url = '/activities'
			}

			$.ajax({
				url: $ajax_url,
				type: $ajax_type,
				dataType: 'json',
				data: $('form#activityForm').serialize(),
				headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				success: function(data) {
					if (data['status'] == "failed") {
						console.log("Error: " + JSON.stringify(data));
						$("#create_activity_primary_alert").css("display", "none");
						if (data["reason"] != undefined) {
							$("#create_activity_error").css("display", "block").html("Could not create/update activity.<br/>Resaon: " + data["reason"]);
						} else {
							$("#create_activity_error").css("display", "block").html("Could not create/update activity.");
						}
						$('#activityModal button[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
					} else {
						$("#create_activity_primary_alert").css("display", "none");
						$("#create_activity_success").css("display", "block");
						window.location.reload();
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Error: " + jqXHR.responseText);
					$("#create_activity_primary_alert").css("display", "none");
					$("#create_activity_error").css("display", "block").html("Could not create/update activity.<br>Error: " + jqXHR.responseText);
					$('#activityModal button[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
				}
			});
		});
	});
</script>
@endsection
