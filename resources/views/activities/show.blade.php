<div class="modal fade" id="showActivityModal" tabindex="-1">
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
				<p class="m-0"><b>Gym:</b> <span class="gym">-</span><span class="action_buttons"></span></p>
               	<p class="m-0"><b>Start:</b> <span class="start">-</span></p>
				<p class="m-0"><b>End:</b> <span class="end">-</span></p>
				<p class="m-0"><b>Coach:</b> <span class="coach">-</span></p>
				<p><b>Location:</b> <span class="location">-</span></p>
                <h5>Description:</h5>
				<div class="description line-wrap">-</div>
				<br />
				
				<div class="updateMembers"></div>
				<br />
				<form id="deleteActivity" class="deleteActivityForm" method="POST">
					@csrf
					<input type="submit" id="deleteActivityButton" class="btn btn-danger" style="display: none" value="Delete Activity">
				</form>

				<p class="table-prefix"><b>Participants <span class="members-booked">-</span>/<span class="max-booked">-</span>
					<span class="members-queued-parentheses"> (<span class="members-queued">-</span> in queue)</span>:</b>
					<span class="add-participant-icon"></span>
				</p>
				<table class="table table-bordered table-sm" id="members-booked-table">
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirm-leave-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body unbook">
                    <h5 class="modal-title h5" style="display: inline;">Are you sure you want to unbook this workout?</h5>
                    <p>If there is a queue, your place will go to the first in queue.</p>
                </div>
                <div class="modal-body unqueue">
                    <h5 class="modal-title h5" style="display: inline;">Are you sure you want to stop queing?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <input type="hidden" name="activity-id" value="">
                    <input type="hidden" name="reload-page" value="">
                    <input type="hidden" name="reopen-modal-after-confirm" value="">
                    <form>
                        <input type="hidden" name="join" value="avboka">
                        <input type="hidden" name="get_users" value="false">
                        <button type="submit" class="btn btn-danger unbook">Unbook</button>
                        <button type="submit" class="btn btn-danger unqueue">Stop queing</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
