<script>
    $(document).ready(function () {
        $('button').on('click', function () {
            var urls = $('#urls').val();
            $.ajax(
                {
                    type: 'POST',
                    url: '/push',
                    data: {
                        urls: urls
                    },
                    success: function (data) {
                        waitForResponse(data);
                    }
                }
            );
        });
    });

    function insertData(data) {
        var obj = jQuery.parseJSON(data);
        $("#" + obj.id).empty().append("<td>" + obj.url + "</td> <td>" + obj.code + "</td> <td>" + obj.title + "</td>");
    }

    function waitForResponse(order) {
        var order_list = jQuery.parseJSON(order);
        var scan = setInterval(
            function () {
                if (typeof Object.keys(order_list)[0] !== "undefined") {
                    var id_order = Object.keys(order_list)[0];
                    var url = order_list[Object.keys(order_list)[0]];

                    delete order_list[Object.keys(order_list)[0]];

                    console.log('REQUIRE ' + id_order);//id
                    $.ajax(
                        {
                            type: 'POST',
                            url: '/listen',
                            cache: false,
                            timeout: 30000,
                            data: {id_order: id_order},
                            beforeSend: function () {
                                $('table').append("<tr id='" + id_order + "'> <td>" + url + "</td> <td> Загрузка...</td> <td></td> </tr>");
                            },
                            success: function (data) {
                                console.log('RESPONSE ' + data);
                                insertData(data);
                            }
                        }
                    );
                }
                else {
                    clearInterval(scan);
                }
            }
            , 1000);
    }
</script>
<style>
    table, tr, th, td {
        border: 1px solid black;
    }
</style>


<textarea name="urls" id="urls" cols="30" rows="10" placeholder="Введите url"></textarea>
<button>Получить информацию</button>
<br/>
<table class="table">
    <tr>
        <th>URL</th>
        <th>Code</th>
        <th>Title</th>
    </tr>
</table>