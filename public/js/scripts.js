




// $.post(
// 	$.ajaxSetup({
//     headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//     }
// });
//     '/dashboard/configinputs.php', // location of your php script
//     // { name: "bob", user_id: 1234 }, // any data you want to send to the script
//     function( data ){  // a function to deal with the returned information

//         $( '.config ').text( data );

//     });

$(document).ready(function(){
	document.getElementById("sched").reset();
});


$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});


	$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


//configure
$(document).ready(function() {
        $("#scannow").click(function(){
            var urlGetInputs = "/dashboard/doscannow" ,
            selected_networks = $(".checknet input:checkbox:checked").map(function(){
      				return $(this).val();
    			}).get(); 
			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { sn: selected_networks }
			}).done(function(msg) {
				alert(msg);	
			  $("#processing-modal").modal("hide");
			  location.reload();
			});            
        });
 });


$(document).ready(function() {
        $("#select_template").change(function(){
            var urlGetInputs = "/dashboard/configinputs" ,
   			 selected_template = $('#select_template option:selected').text();

			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { st: selected_template }
			}).done(function( msg ) {
			  $("#config").html(msg);
			});


            
        });
 });


$(document).ready(function() {
        $(".check").change(function(){
            var urlGetInputs = "/dashboard/network/modify" ,
   			 selected_network = $(this).parent().parent().children().first().text();
   			$.ajax({
				context : this ,
			  type: "POST",
			  url: urlGetInputs,
			  data: { sn: selected_network }
			}).done(function( msg ) {
			 $(this).prev().html(msg);
			});


            
        });
 });




$(document).ready(function() {
        $(".templatetable").click(function(){
            var urlGetInputs = "/dashboard/gettemplate" ,
   			 selectedtable=$(this).text();

			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { t: selectedtable }
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#title').val(selectedtable);
			  $('#title').prop('disabled',true);
			  $('#commands').val(msg);
			  $('#commands').prop('disabled',true);
			});      
        });
 });

// $(document).ready(function() {
//         $("#tabContent").click(function(){
//             var urlGetInputs = "/dashboard/gettemplate" ,
//    			 selectedtable=$(this).text();

// 			$.ajax({
// 			  type: "POST",
// 			  url: urlGetInputs,
// 			  data: { t: selectedtable }
// 			}).done(function( msg ) {
// 			  // $("#config").html(msg);
// 			  $('#title').val(selectedtable);
// 			  $('#title').prop('disabled',true);
// 			  $('#commands').val(msg);
// 			  $('#commands').prop('disabled',true);
// 			});      
//         });
//  });

$(document).ready(function() {
	$(".custom-toolbar").hide();
        $("tr").hover(function(){
            $(this).children().next().show();
        });
 });

$(document).ready(function() {
	$(".custom-toolbar2").hide();
        $("tr").hover(function(){
           $(this).children().last().children().first().show();

        });
 });

$(document).ready(function() {
        $("tr").mouseleave(function(){
            $(".custom-toolbar").hide();
        });
 });

$(document).ready(function() {
        $("tr").mouseleave(function(){
            $(".custom-toolbar2").hide();
        });
 });


$(document).ready(function() {
	$(".delete").click(function(){
		$(".delete").submit();
	});

});

//template
$(document).ready(function() {
	$(".edit").click(function(){
		// $('#title').prop('disabled',false);
		$('#save').val($.trim($(this).parent().parent().first().text()));
		$('#title').val($.trim($(this).parent().parent().first().text()));
		$('#commands').prop('disabled',false);
		$('#title').prop('disabled',true);
		var urlGetInputs = "/dashboard/gettemplate" ,
   			 selectedtable=$.trim($(this).parent().parent().first().text());

			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { t: selectedtable }
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#commands').val(msg);
			});      
        });
	});

$(document).ready(function() {
	$("#add").click(function(){
		$('#commands').prop('disabled',false);
		$('#title').prop('disabled',false);
		$('#save').val("");
		$('#title').val("");
		$('#commands').val("");
	});
});





//setting
$(document).ready(function() {
  		var urlGetInputs = "/dashboard/ping/check";

			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#all-ping').html(msg[0]+msg[1]);
			  $('#success-ping').html(msg[0]);
			  $('#failed-ping').html(msg[1]);
			}); 
});

setInterval(function() {
  		var urlGetInputs = "/dashboard/ping/check";

			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#all-ping').html(msg[0]+msg[1]);
			  $('#success-ping').html(msg[0]);
			  $('#failed-ping').html(msg[1]);
			}); 
}, 1000 * 10);

// setInterval(function() {
//   		var urlGetInputs = "/dashboard/scan/check";

// 			$.ajax({
// 			  type: "POST",
// 			  url: urlGetInputs
// 			}).done(function( msg ) {
// 			  // $("#config").html(msg);
// 			  $('#all-scan').html(msg[0]+msg[1]);
// 			  $('#success-scan').html(msg[0]);
// 			  $('#failed-scan').html(msg[1]);
// 			}); 
// }, 1000 * 10);

setInterval(function() {
  		var urlGetInputs = "/dashboard/notifications/check";
			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( data ) {
				// data-count="5"
				// alert(data);
				$('#notification-content').html(data[0]);
				if (data[1]>0)
				{
					$('#notification-number').attr("data-count",data[1]);
					$('#notification-number').addClass("notification-icon");
				}
				else
				{
					$('#notification-number').removeClass("notification-icon");
				}
			}); 
}, 1000 * 10);



  		var urlGetInputs = "/dashboard/notifications/check";
			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( data ) {
				// data-count="5"
				$('#notification-content').html(data[0]);
				if (data[1]>0)
				{
					$('#notification-number').attr("data-count",data[1]);
					$('#notification-number').addClass("notification-icon");
				}
				else
				{
					$('#notification-number').removeClass("notification-icon");
				}
			}); 


$(document).ready(function() {
	$("#dLabel").click(function(){
  		var urlGetInputs = "/dashboard/notifications/seen";

			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function() {
				$('#notification-number').removeClass("notification-icon");
			}); 
	});
});



$(document).ready(function() {
  		var urlGetInputs = "/dashboard/ping/check";

			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#all-ping').html(msg[0]+msg[1]);
			  $('#success-ping').html(msg[0]);
			  $('#failed-ping').html(msg[1]);
			}); 
});

$(document).ready(function() {
  		var urlGetInputs = "/dashboard/scan/check";

			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			}).done(function( msg ) {
			  // $("#config").html(msg);
			  $('#all-scan').html(msg[0]+msg[1]);
			  $('#success-scan').html(msg[0]);
			  $('#failed-scan').html(msg[1]);
			}); 
});

// document.getElementById("yourLinkId").onclick = function() {
//     document.getElementById("yourFormId").submit();
// }

//settings
$(document).ready(function() {
        $("#profiles").change(function(){
            var urlGetInputs = "/dashboard/setting/getprofile" ,
   			 selected_profile= $('#profiles option:selected').text();

			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { sp: selected_profile }
			}).done(function( msg ) {
			  $("#label").val(msg[0]);
			  $("#pingnombre").val(msg[1]);
			  $("#pingtime").val(msg[2]).change();
			  $("#scannombre").val(msg[3]);
			  $("#scantime").val(msg[4]).change();
			  $("#backupnombre").val(msg[5]);
			  $("#backuptime").val(msg[6]).change();
			});


            
        });
 });



$(document).ready(function() {
        $(".device-table").click(function(){
            var urlGetInputs = "/dashboard/devices/getinterfaces" ,
   			 selectedtable=$(this).children().first().next().text();
			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { t: selectedtable }
			}).done(function( msg ) {
				$('#getinter').html(msg);
			});      
        });
 });


$(document).ready(function() {
        $(".profileselect").change(function(){
            var urlGetInputs = "/dashboard/network/changeprofile" ,
   			 selected_profile = $("option:selected",this).text();
   			 selected_network = $("option:selected",this).val();

			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { sp: selected_profile , sn : selected_network}
			}).done();


            
        });
 });




//table devices

$(document).ready(function() {
  $(".search").keyup(function () {
    var searchTerm = $(".search").val();
    var listItem = $('.results tbody').children('tr');
    var searchSplit = searchTerm.replace(/ /g, "'):containsi('")
    
  $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }
  });
    
  $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e){
    $(this).attr('visible','false');
  });

  $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e){
    $(this).attr('visible','true');
  });

  var jobCount = $('.results tbody tr[visible="true"]').length;
    $('.counter').text(jobCount + ' item');

  if(jobCount == '0') {$('.no-result').show();}
    else {$('.no-result').hide();}
		  });
});


$(document).ready(function() {
$('#device-table').on('click', '.device-table', function(event) {
  $(this).addClass('bg-info').siblings().removeClass('bg-info');
});
});

$(document).ready(function(){
    $('[data-toggle="popover"]').popover(); 
});

$(document).ready(function(){
$('#all-ping').popover();
$('#success-ping').popover();
$('#failed-ping').popover();
});


$(document).ready(function(){
$('#read').popover();
$('#restore').popover();
$('#backup').popover();
});



$(document).ready(function() {

        $(".usertable").click(function(){
            var urlGetInputs = "/dashboard/getuser" ,
   			 selecteduser=$(this).text();
   			 
			$.ajax({
			  type: "POST",
			  url: urlGetInputs,
			  data: { t: selecteduser }
			}).done(function( msg ) {

			  $('#name2').val(msg[0]);
			  $('#password').prop('disabled',true);
			  $('#password-confirm').prop('disabled',true);
			  $('#email').val(msg[1]);
			  $('#role option[value='+msg[2]+']').prop('selected', true);
			  
			});      
        });
 });


$(document).ready(function() {
	$("#resetuser").click(function(){
		$('#password').prop('disabled',false);
		$('#password-confirm').prop('disabled',false);
		$("#role option").prop("selected", false);
	});
});

$(document).ready(function() {
	$("#backupall").click(function(){
            var urlGetInputs = "/dashboard/backup/back" 
   			 // selectedtable=$(this).children().first().next().text();
			$.ajax({
			  type: "POST",
			  url: urlGetInputs
			  // data: { t: selectedtable }
			}).done(function( msg ) {
				$("#processing-modal").modal("hide");
				$('#msgs').html(msg);

			});
	});
});





