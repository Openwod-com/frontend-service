@extends('layouts.app')
@section('title', 'Members')

@section('content')
<!-- <link rel="stylesheet" href="{{ mix('css/activities.css') }}"> -->
<div class="mt-3"></div>
<table id="members-table" style="width:100%" class="display members-dt">
    <thead>
        <tr>
            <th style="width:40px"></th>
            <th>Name</th>
            <th class="hide-on-portrait">Member Since</th>
        </tr>
    </thead>
</table>

<script>
    window.addEventListener('load', () => {
    $('#members-table').DataTable( {
        dom: 'frtip',
        "order": [[ 1, "asc" ]],
        "pageLength": 50,
		ajax: {
			"url": "/api/members",
			"dataSrc": ""
        },
        "columnDefs": [
            { className: "hide-on-portrait", "targets": [ 2 ] }
        ],
        columns: [
            {
                "orderable": false,
                "mDataProp": null,
				fnCreatedCell: function (nTd, sData, oData, iRow, iCol) {
                    if (oData.avatar) {
                        $(nTd).html("<img class='dt-img' src='" + oData.avatar + "'> ");
					} else {
                        $(nTd).html("<img class='dt-img' src='/img/default_user.jpg'> ");
					}
                },
            },
            {
                data: 'name',
                "visible": true,
                "searchable": true,
            },
            {
				data: 'join_date',
				"visible": true,
                "searchable": true
            }
        ],
        "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ members",
            "infoFiltered":   "",
            "infoEmpty":      "Showing 0 to 0 of 0 members"
        } 
    }
    );
    $('input[type="search"]').focus();

    
} );

</script>

<!-- <script src="{{ mix('js/activities.js') }}"></script> -->
@endsection
