var options = {
    valueNames: [
         { data: ['name'] },
         { data: ['category'] } 
    ],
    listClass: 'pp-modules-list',
    searchClass: 'pp-modules-search'
};

var widgetList = new List('pp-list', options);

function updateList() {
    var category  =   $("input[name=category]:checked").val();
    //console.log(category);

    widgetList.filter(function (item) {
		var categoryFilter = false;
		
		if( category == "all" )
		{ 
            categoryFilter = true;
            //console.log(categoryFilter);
		} else {
            //console.log(item.values());
            categoryFilter = item.values().category == category;	
            //console.log(categoryFilter);
        }

        return categoryFilter;
    });

    widgetList.update();
    //console.log('Filtered: ' + category);
}

$(  function() {

    //updateList();
    
    $('.no-result').hide();

    if( $("input[name=category]:checked") )
        {   
           $("input[name=category]:checked").parent().addClass("checked");
        }

    $("input[name=category]").change(updateList);

    widgetList.on('updated', function (list) {

        $("input[name=category]").parent().removeClass("checked");

        if($("input[name=category]:checked")) {

            $("input[name=category]:checked").parent().addClass("checked");

         }         

        if ( list.matchingItems.length > 0 ) {
            //console.log(list);
            $('.no-result').hide();
        } else {
            $('.no-result').show()
        }

        widgetList.list.childNodes.forEach(function(element){

                var elem = $('#' + element.id);
                //elem.addClass('animated fadeIn slideInUp');
                elem.css('opacity', "0");
                setTimeout(function(){
                    elem.animate({top:})
                }, 50);

            });
      });
  });