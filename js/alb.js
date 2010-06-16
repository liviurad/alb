$(function() {
    setTimeout(clearCache, 3000);
});

function clearCache() {
  $.ajax({
    url: "run.php?cmd=trim_cache",
    success: clearCacheSuccess,
    error: clearCacheError,
    dataType: 'xml'
  });
}

function clearCacheSuccess(data) {
  if ($(data).find("status").text() != "OK" || $(data).find("show_message").text() == "yes") {
    showMessage($(data).find("message").text());
  }
}

function clearCacheError() {
  showMessage("<span class=\"red\">Trim cache failed!</span>");
}

function showMessage(msg) {
  $('#message').html(msg);
  $('#message').slideDown('fast', function() { setTimeout(hideMessage, 3000); });
  positionMessage();
  $(window).bind('scroll', positionMessage);
}

function hideMessage() {
  $('#message').slideUp('fast');
  $(window).unbind('scroll', positionMessage);
}

function positionMessage() {
  var top = $(window).scrollTop() - 2;
  var left = Math.round($(window).width() / 2) - Math.round($('#message').width() / 2);
  $('#message').offset({top: top, left: left});
}


