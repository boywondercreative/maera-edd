var $j = jQuery.noConflict();

$j(window).load(function(){
  
  // isotope container
  var $container = $j(".downloads-list.grid");

  $container.isotope({
    // layout
    layoutMode: "sloppyMasonry",
    itemSelector: ".type-download",
    animationEngine: "best-available",
    // sorting data
    getSortData: {
      title : function ( $elem ) {
        return $elem.find(".title").text();
    }
    }
  });

  // sort items on button click
  $j('#isotope-sort button').click(function() {
    var sortByValue = $j(this).attr('data-sort-by');
    
    if (sortByValue == 'title-asc') {
      $container.isotope({ sortBy: 'title', sortAscending: true }); 
    }
    if (sortByValue == 'title-dsc') {
      $container.isotope({ sortBy: 'title', sortAscending: false });  
    }
    
  });

});