<!-- jQuery  -->
<script src="/js/jquery.min.js"></script>
<script src="/js/popper.min.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/waves.js"></script>
<script src="/js/jquery.slimscroll.js"></script>
<script src="/js/jquery.scrollTo.min.js"></script>

<!-- App js -->
<script src="/js/jquery.core.js"></script>
<script src="/js/jquery.app.js"></script>

<script>
  $(document).on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    if ( $( "#deleteModal" ).length ) {
      var link = button.data('link');
      $("#deleteModal").attr("action", link);
    }
  });
</script>