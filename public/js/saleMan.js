
$('#country-dropdown').on('change', function () {
    var idCountry = this.value;
    $("#zone-dropdown").html('');
    $.ajax({
        url: "{{ url('api/fetch-zones') }}",
        type: "POST",
        data: {
            country_id: idCountry,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function (result) {
            $('#zone-dropdown').html(
                '<option value="">-- Select zone --</option>');
            $.each(result.zones, function (key, data) {
                $("#zone-dropdown").append('<option value="' + data
                    .id + '">' + data.name + '</option>');
            });
            $('#sector-dropdown').html(
                '<option value="">-- Select Belt --</option>');
            $('#area-dropdown').html('<option value="">-- Select Area --</option>');

        }
    });
});

/*------------------------------------------
--------------------------------------------
zone Dropdown Change Event
--------------------------------------------
--------------------------------------------*/
$('#zone-dropdown').on('change', function () {
    var idZone = this.value;
    $("#sector-dropdown").html('');
    $.ajax({
        url: "{{ url('api/fetch-sectors') }}",
        type: "POST",
        data: {
            zone_id: idZone,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function (res) {
            $('#sector-dropdown').html(
                '<option value="">-- Select Belt --</option>');
            $.each(res.sectors, function (key, value) {
                $("#sector-dropdown").append('<option value="' + value
                    .id + '">' + value.name + '</option>');
            });
            $('#area-dropdown').html('<option value="">-- Select Area --</option>');
        }
    });
});

$('#sector-dropdown').on('change', function () {
    var sectorId = this.value;
    $("#area-dropdown").html('');
    $.ajax({
        url: "{{ url('api/fetch-areas') }}",
        type: "POST",
        data: {
            sector_id: sectorId,
            _token: '{{ csrf_token() }}'
        },
        dataType: 'json',
        success: function (resul) {
            $('#area-dropdown').html('<option value="">-- Select Area --</option>');
            $.each(resul.areas, function (key, value) {
                $("#area-dropdown").append('<option value="' + value
                    .id + '">' + value.name + '</option>');
            });
        }
    });
});

