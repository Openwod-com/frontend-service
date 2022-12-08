<div class="modal fade" id="editActivityModal" tabindex="-1">                                                                                                                                                                                          [91/97817]
	<div class="modal-dialog" role="dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title h5" style="display: inline;">-</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body loading">
				<h5>Laddar...</h5>
				<p class="error" style="color: red; display: none;">Ett fel uppstod.</p>
			</div>
			<div class="modal-body content" style="display: none">
				<form role="form" method="POST">
					@csrf
					<div class="form-group">
						<label for="edit-subject" class="control-label">Aktivitet:</label>
						<input type="text" name="edit-subject" class="form-control" id="edit-subject" placeholder="Aktivitet" required>
					</div>
					<div class="form-group">
						<label for="edit-description" class="control-label">Beskrivning:</label>
						<textarea name="edit-description" class="form-control" id="edit-description" placeholder="Beskrivning" required></textarea>
					</div>
					<div class="form-group">
						<label for="edit-startTime" class="control-label">Starttid:</label>
						<input type="text" class="form-control" name="start" id="edit-startTime" placeholder="yyyy-mm-dd --:--" required>
					</div>
					<div class="form-group">
						<label for="duration" class="control-label">Längd:</label>
						<select name="duration" id="duration" class="form-control">
							<option value="PT15M">15 minuter</option>
							<option value="PT30M">30 minuter</option>
							<option value="PT45M">45 minuter</option>
							<option value="PT1H">1 timme</option>
							<option value="PT1H15M">1 och 15 minuter</option>
							<option value="PT1H30M">1 och 30 minuter</option>
							<option value="PT1H45M">1 och 45 minuter</option>
							<option value="PT2H">2 timme</option>
						</select>
					</div>
					<div class="form-group">
						<label for="maximum_users" class="control-label">Max antal deltagare:</label>
						<input type="text" name="maximum_users" class="form-control" id="maximum_users" placeholder="Max antal deltagare" value="30" required>
					</div>
					<div class="form-group">
						<input type="submit" class="btn btn-success" value="Spara">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Avbryt</button>
					</div>
				</form>
				<div id="create_activity_error" class="alert alert-danger" style="display: none;" role="alert">Lyckades inte uppdatera träningspasset.</div>
				<div id="create_activity_primary_alert" class="alert alert-primary" style="display: none;" role="alert">Uppdaterar träningspasset...</div>
				<div id="create_activity_success" class="alert alert-success" style="display: none;" role="alert">Träningspasset är nu uppdaterat!</div>
			</div>
		</div>
	</div>
</div>

@section('scripts3')
<script>
	$(document).ready(function() {
		// Define global variables
		let openModal;

		// Fix so the modal dosen't open when you press the button in the table row.
		$('table tr').on("click", "button", function(e) {
			e.stopPropagation();
		});

		$("#editActivityModal").on('show.bs.modal', function (event) {
			// Get id for activity
			let id = $(event.relatedTarget).data('id');
			// Save opened modals id to global variable
			openModal = id;

			// Get data specific to the activity id and populate the data
			$.ajax({
				url: '/activities/'+id+'/edit',
				type: 'GET',
				dataType: 'json',
				data: $('form#editActivityModal').serialize(),
				success: function(data) {
					let modal = $('#editActivityModal');
					modal.find(".modal-title").text('Uppdatera: ' + data['subject'] + ' - ' + data['start_formated']);
					modal.find("#edit-subject").val(data['subject']);
					modal.find("#edit-starttime").text(data['start']);
					modal.find("#edit-description").text(data['description']);
					modal.find(".members-booked").text(data['members_booked']);
					modal.find(".max-booked").text(data['maximum_users']);

					modal.find(".content").show();
					modal.find(".loading").hide();
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Error: " + jqXHR.responseText);
					modal.find(".loading .error").show();
				}
			});
		});

		$("#editActivityModal").on('hidden.bs.modal', function(event) {
			// Reset to loading when closing modal
			$("#editActivityModal .loading").show();
			$("#editActivityModal .content").hide();
			$("#editActivityModal .loading .error").hide();
		});

		// Update members of an activity
		$("#editActivityModal .updateMembers").on("submit", function(e) {
			e.preventDefault();
			let modal = $('#editActivityModal');
			let form = $('#editActivityModal .updateMembers form#updateMembersForm');
			form.find('button[type="submit"]').attr('disabled','disabled').css("cursor", "not-allowed");
			$.ajax({
				url: '/activities/'+openModal+'/uppdateMembers',
				type: 'POST',
				dataType: 'json',
				data: form.serialize(),
				success: function(data) {
					if (data['status'] == "failed") {
						console.log("Error: " + JSON.stringify(data));
						if(data['reason'] == 'Activity is full') {
							form.empty();
							form.append('<button class="btn btn-danger" disabled style="cursor: not-allowed">Fullt</button>');
						}
						form.find('button[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
					} else {
						// Update button to new state
						modal.find(".updateMembers").empty();
						let html = "";
						const buttonTd = $("tr[data-id=\""+openModal+"\"] td:last-child()");
						if(data['is_member']) {
							html = '<form id="updateMembersForm" action="/activities/'+openModal+'/uppdateMembers" method="POST">@csrf<input type="hidden" name="join" value="avboka"><input type="hidden" name="get_users" value="true"><button type="submit" class="btn btn-secondary" value="Avboka">Avboka</button></form>';
							modal.find(".updateMembers").append(html);
							buttonTd.empty().append(html);
						} else if(data['is_full']){
							html = '<button class="btn btn-danger" disabled style="cursor: not-allowed">Fullt</button>';
							modal.find(".updateMembers").append(html);
							buttonTd.empty().append(html);
						} else {
							html = '<form id="updateMembersForm" action="/activities/'+openModal+'/uppdateMembers" method="POST">@csrf<input type="hidden" name="join" value="boka"><input type="hidden" name="get_users" value="true"><button type="submit" class="btn btn-success" value="Boka">Boka</button></form>';
							modal.find(".updateMembers").append(html);
							buttonTd.empty().append(html);
						}
	
						// Update members list
						modal.find("#members-table tbody").empty();
						data['users'].forEach(async function(user) {
							let url = user['avatar'];
							if(user['avatar'] == undefined)
								url = "/img/default_user.jpg";
							
							await modal.find("#members-table tbody").append("<tr><td><img src=\""+url+"\" alt=\"Bild\"></td><td>"+user['name']+"</td></tr>");
						});

						// Update number of booked members
						modal.find(".members-booked").text(data['members_booked']);
						$("tr[data-id=\""+openModal+"\"] .members-booked").text(data['members_booked']);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					console.log("Error: " + jqXHR.responseText);
					form.find('button[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
				}
			});
		});
	});

	$('#confirm-delete').on('click', function(e) {
		let id = $("#edit-id").val();
		$.ajax({
			url: '/admin/ranks/'+id,
			type: 'DELETE',
			dataType: 'json',
			success: function(data) {
				if (data['status'] == "failed") {
				console.log("Error: " + JSON.stringify(data));
				$("#edit_rank_primary_alert").css("display", "none");
				if (data["reason"] != undefined) {
				$("#edit_rank_error").css("display", "block").html("Lyckades inte radera ranken: " + $("#edit-name").val() + "<br/>Anledning: " + data["reason"]);
				} else {
				$("#edit_rank_error").css("display", "block").html("Lycjades inte radera ranken: " + $("#edit-name").val());
				}
				$('#editRankModal button[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
				} else {
				$("#edit_rank_primary_alert").css("display", "none");
				$("#edit_rank_success").css("display", "block");
				window.location.reload();
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log("Error: " + jqXHR.responseText);
			}
		});
	});
</script>
@endsection