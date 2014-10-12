(function ($) {

  // Property relationship

  papi.properties.relationship = {

    /**
     * Add page to list of selected pages.
     *
     * @param $this
     */

    add: function ($this) {
      var $li = $this.clone(),
          $list = $this.closest('.papi-property-relationship').find('.relationship-right ul');

      $li.find('span.icon').removeClass('plus').addClass('minus');
      $li.find('input').attr('name', $li.find('input').data('name'));
      $li.appendTo($list);
    },

    /**
     * Remove the selected page.
     *
     * @param $this
     */

    remove: function ($this) {
      $this.remove();
    },

    /**
     * Search for a page in the list.
     *
     * @param $this
     */

    search: function ($this) {
      var $list = $this.closest('.papi-property-relationship').find('.relationship-left ul'),
          val   = $this.val();

      $list.find('li').each(function () {
        var $li = $(this);
        $li[$li.text().toLowerCase().indexOf(val) === -1 ? 'hide' : 'show']();
      });
    }

  };

  // Events

  $(document).on('click', '.papi-property-relationship .relationship-left li', function (e) {
    e.preventDefault();

    papi.properties.relationship.add($(this));
  });

  $(document).on('click', '.papi-property-relationship .relationship-right li', function (e) {
    e.preventDefault();

    papi.properties.relationship.remove($(this));
  });

  $(document).on('keyup', '.papi-property-relationship .relationship-left .relationship-search input[type=search]', function (e) {
    e.preventDefault();

    papi.properties.relationship.search($(this));
  });

})(jQuery);