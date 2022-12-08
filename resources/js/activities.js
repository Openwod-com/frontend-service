// Global variables
let openModal;

function getBookHtmlInput(activityId) {
    return '<form id="updateMembersForm" action="/activities/'+activityId+'/uppdateMembers" method="POST"><input type="hidden" name="join" value="boka"><input type="hidden" name="get_users" value="true"><input type="submit" class="btn btn-success" value="Book"></form>';
}

function getUnbookHtmlInput(activityId, getUsers = false) {
    return '<form id="updateMembersForm" action="/activities/'+activityId+'/uppdateMembers" method="POST"><input type="hidden" name="join" value="avboka"><input type="hidden" name="get_users" value="'+getUsers+'"><input data-dismiss="modal" data-toggle="modal" data-target="#confirm-leave-modal" data-unbook-type="unbook" type="submit" class="btn btn-secondary" value="Unbook"/></form>';
}

function getQueueHtmlInput(activityId) {
    return '<form id="updateMembersForm" action="/activities/'+activityId+'/uppdateMembers" method="POST"><input type="hidden" name="join" value="boka"><input type="hidden" name="get_users" value="true"><input type="submit" class="btn btn-warning" value="Queue"></form>';
}

function getUnqueueHtmlInput(activityId, getUsers = false) {
    return '<form id="updateMembersForm" action="/activities/'+activityId+'/uppdateMembers" method="POST"><input type="hidden" name="join" value="avboka"><input type="hidden" name="get_users" value="'+getUsers+'"><input data-dismiss="modal" data-toggle="modal" data-target="#confirm-leave-modal" data-unbook-type="unqueue" type="submit" class="btn btn-danger" value="Stop queing"/></form>';
}

function saveDataToConfirmUnbook(activityId, reloadPage = false, getUsers = false, openModalAfterConfirm = false) {
    $('#confirm-leave-modal input[name="activity-id"]').val(activityId);
    $('#confirm-leave-modal input[name="reload-page"]').val(reloadPage);
    $('#confirm-leave-modal input[name="reopen-modal-after-confirm"]').val(openModalAfterConfirm);
    $('#confirm-leave-modal input[name="get_users"]').val(getUsers);
}

function updateMembers(form, _callback) {
    
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        dataType: 'json',
        data: form.serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
			console.log(data);
			
            _callback(data);
            return data;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            _callback(jqXHR, textStatus, errorThrown);
            console.log("Error: " + jqXHR.responseText);
        }
    });
}

function updateMembersGraphically(form, modal, status, activityId, users, queued_users, membersBooked, membersQueued, maximum_users, isMember = false, error = undefined, isFull = false, in_queue = false, isAdmin = false) {
    if (status == "failed") {
        console.log("Error: " + JSON.stringify(error));
        if(error == 'Activity is full') {
            form.empty();
            form.append(getQueueHtmlInput(activityId));
        }
        form.find('[type="submit"]').removeAttr('disabled').css("cursor", "pointer");
    } else {
        // Update button to new state
        modal.find(".updateMembers").empty();
        let html = "";
        if(in_queue) {
            modal.find(".updateMembers").append(getUnqueueHtmlInput(activityId));
        } else if(isMember) {
            html = getUnbookHtmlInput(activityId, true);
            modal.find(".updateMembers").append(html);
        } else if(isFull) {
            modal.find(".updateMembers").append(getQueueHtmlInput(activityId));
        } else {
            html = getBookHtmlInput(activityId);
            modal.find(".updateMembers").append(html);
        }

        // Update members list
        modal.find("#members-booked-table tbody").empty();
        console.log(users);
        
        users.forEach(async function(user) {
            let url = user['avatar'];
            if(user['avatar'] == undefined)
                url = "/img/default_user.jpg";
            
            var user_html;
            user_html = '<tr><td><img class="members-table-img" src="' + url + '" alt="Bild"></td><td>' + user['name'];
            if (isAdmin)
                user_html += '<img class="participant-action-img" onclick="remove_participant(' + user['pivot']['user_id'] + ')" title="Remove Participant" src="/img/icon_delete.png">';
            user_html += '</td></tr>';
            await modal.find("#members-booked-table tbody").append(user_html);
        });

        queued_users.forEach(async function(user) {
            let url = user['avatar'];
            if(user['avatar'] == undefined)
                url = "/img/default_user.jpg";
            
            var user_html;
            user_html = '<tr class="queue"><td><img class="members-table-img" src="' + url + '" alt="Bild"></td><td>' + user['name'] + ' (Queing #' + user['place_in_queue'] + ')';
            if (isAdmin)
                user_html += '<img class="participant-action-img" onclick="remove_participant(' + user['pivot']['user_id'] + ')" title="Remove Participant" src="/img/icon_delete.png">';
            user_html += '</td></tr>';
            await modal.find("#members-booked-table tbody").append(user_html);

        });

        // Update number of booked members
        modal.find(".members-booked").text(isFull ? maximum_users : membersBooked);
        if(membersQueued > 0) {
            modal.find('.members-queued-parentheses').show();
            modal.find(".members-queued").text(membersQueued);
        } else {
            modal.find('.members-queued-parentheses').hide();
        }
        $("tr[data-id=\""+activityId+"\"] .members-booked").text(membersBooked > maximum_users ? maximum_users : membersBooked);
        if(membersQueued <= 0)
            $("tr[data-id=\""+activityId+"\"] .members-queued-table").text('');
        else 
            $("tr[data-id=\""+activityId+"\"] .members-queued-table").text('('+membersQueued+')');

        if (isMember)
            $('tr[data-id="'+activityId+'"]').addClass('booked-text-color');
        else
            $('tr[data-id="'+activityId+'"]').removeClass('booked-text-color');


    }
}

window.create_activity = function(id) {
    let modal = $('#activityModal');
    modal.find(".modal-title").text('Create Activity');

    modal.find("#gym")[0].selectedIndex = 0;
    modal.find("#activityId").val("");
    modal.find("#subject").val("");
    modal.find("#act-description").text("");
    modal.find("#act-location").val("");
    modal.find("#coach").val("");
    modal.find("#startTime").val("");
    modal.find("#duration").val("PT1H");
    modal.find("#maximum_users").val("30");

    $('#activityModal').modal()
    return;
}

window.edit_activity = function(id) {
    console.log("Edit Activity");
    let modal = $('#activityModal');
    modal.find(".modal-title").text('Edit Activity');

    $.ajax({
        url: '/activities/'+id+'/edit',
        type: 'GET',
        dataType: 'json',
        data: $('form#activityModal').serialize(),
        success: function(data) {
            console.log("Ajax success");
            modal.find("#activityId").val(id);
            modal.find("#gym").val(data['box_id']);
            modal.find("#subject").val(data['subject']);
            modal.find("#act-description").text(data['description']);
            modal.find("#act-location").val(data['location']);
            modal.find("#coach").val(data['coach']);
            modal.find("#startTime").val(data['start']);
            modal.find("#duration").val(data['duration']);
            modal.find("#maximum_users").val(data['maximum_users']);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + jqXHR.responseText);
        }
    });

    $('#activityModal').modal()
    return;
}

window.copy_activity = function(id) {
    let modal = $('#activityModal');
    modal.find(".modal-title").text('Create Activity');

    $.ajax({
        url: '/activities/'+id+'/edit',
        type: 'GET',
        dataType: 'json',
        data: $('form#activityModal').serialize(),
        success: function(data) {
            modal.find("#activityId").val("");
            modal.find("#gym").val(data['box_id']);
            modal.find("#subject").val(data['subject']);
            modal.find("#act-description").text(data['description']);
            modal.find("#act-location").val(data['location']);
            modal.find("#coach").val(data['coach']);
            modal.find("#startTime").val("");
            modal.find("#duration").val(data['duration']);
            modal.find("#maximum_users").val(data['maximum_users']);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + jqXHR.responseText);
        }
    });

    $('#activityModal').modal()
    return;
}

window.add_participant = function(activity_id) {
    console.log("Add Participant");
    let modal = $('#showAddParticipantModal');
    modal.find(".modal-title").text('Add Participant');

    var table = $('#participant-add-table').DataTable();
    table.ajax.url( '/api/members?activity_id=' + activity_id ).load();
    $('#showAddParticipantModal').modal()
}

window.remove_participant = function(member_id) {
    console.log("Remove Participant: " + member_id);
    var fd = new FormData();
    fd.append('join', 'unbook' );
    fd.append('get_users', 'true' );
    fd.append('userid', member_id );
    fd.append('action', '/activities/'+openModal+'/uppdateMembers');

    let form = $('#showActivityModal .updateMembers form#updateMembersForm');
    let modal = $('#showActivityModal');

    $.ajax({
        url: '/activities/'+openModal+'/uppdateMembers',
        type: 'POST',
        processData: false,
        contentType: false,
        dataType: 'json',
        data: fd,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response);
            updateMembersGraphically(form, modal, response['status'], openModal, response['users'], response['queued_users'], response['members_booked'], response['members_queued'], response['maximum_users'], response['is_member'], response['reason'], response['is_full'], response['in_queue'], response['is_admin']);
            $('#showAddParticipantModal').modal('hide');
            return response;
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error: " + jqXHR.responseText);
        }
    });
}

$(document).ready(function() {
    // Fix so the modal dosen't open when you press the button in the table row.
    $('#activities-table tr').on("click", "input", function(e) {
        e.stopPropagation();
        console.log("Test");
        
        if($(this).attr('data-target') == "#confirm-leave-modal") {
            // Open confirm modal to leave an activity
            if($(this).attr('data-unbook-type') == "unbook") {
                $('.unbook').show();
                $('.unqueue').hide();
            } else if($(this).attr('data-unbook-type') == "unqueue") {
                $('.unqueue').show();
                $('.unbook').hide();
            }

            $("#confirm-leave-modal").modal();
            saveDataToConfirmUnbook($(this).attr('data-id'), true);
        }
    });

    $("#showActivityModal").on('show.bs.modal', function (event) {
        // Get id for activity and save id to global variable
        openModal = $(event.relatedTarget).data('id');
        let modal = $('#showActivityModal');

        // Get data specific to the activity id and populate the data
        $.ajax({
            url: '/activities/'+openModal+'/edit',
            type: 'GET',
            dataType: 'json',
            data: $('form#showActivityModal').serialize(),
            success: function(data) {
                modal.find(".modal-title").text(data['subject'] + " - " + data['start_formated']);
                modal.find(".gym").html('<a href="/gym/' + data['box_id'] + '">' + data['box_name'] + '</a>');
                modal.find(".start").text(data['start_formated']);
                modal.find(".end").text(data['end_formated']);
				modal.find(".location").text(data['location'] == '' ? 'Ej angivet' : data['location']);
				modal.find(".coach").text(data['coach'] == '' ? 'Ej angivet' : data['coach']);
                modal.find(".description").text(data['description']);
                modal.find(".members-booked").text(data['members_booked']);
				modal.find(".max-booked").text(data['maximum_users']);
				if(data['members_queued'] > 0) {
					modal.find('.members-queued-parentheses').show();
					modal.find(".members-queued").text(data['members_queued']);
				} else
					modal.find('.members-queued-parentheses').hide();

                    
                modal.find(".updateMembers").empty();
                if(data['is_member'])
                    modal.find(".updateMembers").append(getUnbookHtmlInput(openModal, true));
                else if(data['in_queue'])
                    modal.find(".updateMembers").append(getUnqueueHtmlInput(openModal));
                else if(data['is_full'])
                    modal.find(".updateMembers").append(getQueueHtmlInput(openModal));
                else
                    modal.find(".updateMembers").append(getBookHtmlInput(openModal));
 
                if (data['is_admin']) {
                    modal.find('#deleteActivityButton').show();
                    modal.find(".action_buttons").html('<img class="action-img" onclick="edit_activity(' + openModal + ')" title="Edit Activity" src="/img/icon_edit.png"></img>' +
                        '<img class="action-img" onclick="copy_activity(' + openModal + ')" title="Copy activity" src="/img/icon_copy.png"></img>'
                    );
                    modal.find(".add-participant-icon").html('<img class="header-action-img" onclick="add_participant(' + openModal + ')" title="Add Participant" src="/img/icon_plus.png"></img>');
                } 
                else {
                    modal.find('#deleteActivityButton').hide();
                }
                    // Update Delete Activity Button with correct action:
                modal.find(".deleteActivityForm").attr('action', '/activities/'+openModal+'/delete');

                $("#members-booked-table tbody").empty();
                data['users'].forEach(async function(user) {
                    let url = user['avatar'];
                    if(user['avatar'] == undefined)
                        url = "/img/default_user.jpg";
                    
                    var user_html;
                    user_html = '<tr><td><img class="members-table-img" src="' + url + '" alt="Bild"></td><td>' + user['name'];
                    if (data['is_admin'])
                        user_html += "<img class='participant-action-img' onclick='remove_participant(" + user['pivot']['user_id'] + ")' title='Remove Participant' src='/img/icon_delete.png'>";
                    user_html += '</td></tr>';
                    $("#members-booked-table tbody").append(user_html);
				});
				data['queued_users'].forEach(async function(user) {
                    let url = user['avatar'];
                    if(user['avatar'] == undefined)
                        url = "/img/default_user.jpg";
                    
                    var user_html;
                    user_html = '<tr class="queue"><td><img class="members-table-img" src="'+url+'" alt="Bild"></td><td>'+user['name']+' (Queing #'+user['place_in_queue']+')';
                    if (data['is_admin'])
                        user_html += "<img class='participant-action-img' onclick='remove_participant(" + user['pivot']['user_id'] + ")' title='Remove Participant' src='/img/icon_delete.png'>";
                    user_html += '</td></tr>';
                    $("#members-booked-table tbody").append(user_html);
                });
                modal.find(".content").show();
                modal.find(".loading").hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Error: " + jqXHR.responseText);
                modal.find(".loading .error").show();
            }
        });
    });

    $('#participant-add-table').DataTable( {
        dom: 'frtip',
        "order": [[ 0, "asc" ]],
        "pageLength": 10,
        ajax: {
            "url": "/api/members?boxid=0",
            "dataSrc": ""
        },
        "bSort" : false,
        columns: [
            {
                data: 'id',
                "visible": false,
                "searchable": false
            },
            {
                data: 'name',
                "visible": false,
                "searchable": true
            },
            {
                "mDataProp": null,
                fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                    if (oData.avatar) {
                        $(nTd).html("<img class='avatar-img' src='" + oData.avatar + "'> " + oData.name);
                    } else {
                        $(nTd).html("<img class='avatar-img' src='/img/default_user.jpg'> " + oData.name);
                    }
                }
            }
        ],
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ members",
            "infoFiltered":   "",
            "infoEmpty":      "Showing 0 to 0 of 0 members"
        }
    }
    );

    $("#showActivityModal").on('hidden.bs.modal', function(event) {
        // Reset to loading when closing modal
        $("#showActivityModal .loading").show();
        $("#showActivityModal .content").hide();
        $("#showActivityModal .loading .error").hide();
    });

    // Update members of an activity (event only active in open modal)
    $("#showActivityModal .updateMembers").on("click", "input[type=\"submit\"]", function(e) {
        e.preventDefault();
        let modal = $('#showActivityModal');

        if($(this).attr('data-target') == "#confirm-leave-modal") {
            // Open confirm modal to leave an activity
            if($(this).attr('data-unbook-type') == "unbook") {
                $('.unbook').show();
                $('.unqueue').hide();
                
                // Save data to confirm toggle
                // Input/button opens the modal automaticly
                saveDataToConfirmUnbook(openModal, false, false, true);
                return;
            } else if($(this).attr('data-unbook-type') == "unqueue") {
                $('.unqueue').show();
                $('.unbook').hide();

                // Save data to confirm toggle
                // Input/button opens the modal automaticly
                saveDataToConfirmUnbook(openModal, false, false, true);
                return;
            }

            $("#confirm-leave-modal").modal();
            saveDataToConfirmUnbook($(this).attr('data-id'), true);
        }
        
        // Send ajax to book and refresh user data in modal, to show that the user is booked
        let form = $('#showActivityModal .updateMembers form#updateMembersForm');
        form.find('[type="submit"]').attr('disabled','disabled').css("cursor", "not-allowed");
        updateMembers(form, function(response) {
            updateMembersGraphically(form, modal, response['status'], openModal, response['users'], response['queued_users'], response['members_booked'], response['members_queued'], response['maximum_users'], response['is_member'], response['reason'], response['is_full'], response['in_queue'], response['is_admin']);
        });

    });

    // Join/leave an activity (inputs/buttons in main table/list)
    $("#activities-table").on("submit", "form", function(e) {
        e.preventDefault();
        let form = $(this);

        const pressedButton = form.find('[type="submit"]');
        pressedButton.attr('disabled','disabled').css("cursor", "not-allowed");
        updateMembers(form, function() {
            window.location.reload();
        });
    });

    // If info modal should be opend after leaving an activity, add data-dismiss to confirm button, so the button automaticly vill close the open modal, js vill open the info modal
    $("#confirm-leave-modal").on('show.bs.modal', function (event) {
        let reopenModal = $('#confirm-leave-modal input[name="reopen-modal-after-confirm"]').val() == "false" ? false : true;
        if(reopenModal)
            $('#confirm-leave-modal [type="submit"]').attr('data-dismiss', 'modal');
    });


    $('#confirm-leave-modal').on('click', '[type="submit"]', function(e) {
        e.preventDefault();
        
        let reopenModal = $('#confirm-leave-modal input[name="reopen-modal-after-confirm"]').val() == "false" ? false : true;
        let reloadPage = $('#confirm-leave-modal input[name="reload-page"]').val() == "false" ? false : true;
        let activityId = $('#confirm-leave-modal input[name="activity-id"]').val();

        $("#confirm-leave-modal form").attr('action', '/activities/'+activityId+'/uppdateMembers');
        if(reloadPage) {
            updateMembers($("#confirm-leave-modal form"), function() {
                window.location.reload();
            });
        } else if(reopenModal) {
            updateMembers($("#confirm-leave-modal form"), function(data) {
                $('tr[data-id="'+activityId+'"]').click();
                $("tr[data-id=\""+activityId+"\"] .members-booked").text(data['members_booked'] > data['maximum_users'] ? data['maximum_users'] : data['members_booked']);
                if(data['members_queued'] <= 0)
                    $("tr[data-id=\""+activityId+"\"] .members-queued-table").text('');
                else 
                    $("tr[data-id=\""+activityId+"\"] .members-queued-table").text('('+data['members_queued']+')');
                $('tr[data-id="'+activityId+'"]').removeClass('booked-text-color');
            });
        } else {
            updateMembers($("#confirm-leave-modal form"));
        }
    });

    $('#participant-add-table').on('click', 'td', function () {
        var table = $('#participant-add-table').DataTable();

        var member_id = table.row( $(this).parents('tr') ).data()["id"];
        if (member_id != null) {
            console.log("Add Member: " + member_id);
            var fd = new FormData();
            fd.append('join', 'boka' );
            fd.append('get_users', 'true' );
            fd.append('userid', member_id );
            fd.append('action', '/activities/'+openModal+'/uppdateMembers');

            let form = $('#showActivityModal .updateMembers form#updateMembersForm');
            let modal = $('#showActivityModal');

            $.ajax({
                url: '/activities/'+openModal+'/uppdateMembers',
                type: 'POST',
                processData: false,
                contentType: false,
                dataType: 'json',
                data: fd,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log(response);
                    updateMembersGraphically(form, modal, response['status'], openModal, response['users'], response['queued_users'], response['members_booked'], response['members_queued'], response['maximum_users'], response['is_member'], response['reason'], response['is_full'], response['in_queue'], response['is_admin']);
                    $('#showAddParticipantModal').modal('hide');
                    return response;
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log("Error: " + jqXHR.responseText);
                }
            });
        }
        else {
            console.log("Member ID null");
        }
    });
});
