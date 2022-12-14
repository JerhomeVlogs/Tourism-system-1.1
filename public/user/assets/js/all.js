
$(document).ready(function ()
{
    setInterval(dashboard, 2000);
    book_count();


    function leave_manual ()
    {
        $.ajax ({
            type: "GET",
            url: "/leave/manual",
            dataType: "json",
            success: function (response) 
            {
                console.log(response.check);
            }
 
        });
    }

    function dashboard ()
    {
        leave_manual();
        
        $.ajax ({
            type: "GET",
            url: "/user/dashboard/fetch",
            dataType: "json",
            success: function (response) 
            {
                let l = response.count;
                $('#dashboard').html("");
                $('#dahboard2').html("");
                for (let i = 0; i < l; i++)
                {
                    $('#dashboard').append('<div class="col-sm-6 col-xl-4">\
                    <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">\
                      <i class="fa fa-users fa-3x text-primary"></i>\
                      <div id="falls_count" class="ms-3">\
                          <p class="mb-2">'+response.data[i].name+'</p>\
                          <h6 id="patar_count" class="text-center mb-0">'+response.data[i].visit_count+'</h6>\
                      </div>\
                    </div>\
                  </div>')

                  $('#dahboard2').append('<div class="col-sm-6 col-xl-4">\
                  <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">\
                    <i class="fa fa-users fa-3x text-primary"></i>\
                    <div id="falls_count" class="ms-3">\
                        <p class="mb-2">'+response.data[i].name+'</p>\
                        <h6 id="patar_count" class="text-center mb-0">'+response.data[i].total_visit+'</h6>\
                    </div>\
                  </div>\
                </div>')
                }

            }
 
        });
    }
    

    function book_count ()
    {

        $.ajax ({
            type: "GET",
            url: "/book2/count",
            dataType: "json",
            success: function (response) 
            {
                $('#locations').html(" ");
                $('#locations').append('<option value="">Destination</option>');
                $.each(response.locations, function (key, list)
                {
                    $('#locations').append('<option value="'+list.name+'">'+list.name+' ('+list.visit_count+')</option>');
                });

            }
 
        });
    } 




});
