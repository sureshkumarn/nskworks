// JS for Population Growth Module
var NSKWORKS = window.NSKWORKS || {};

NSKWORKS.setdata = function(key,value){
	// Code to save data
	
}

NSKWORKS.getdata = function(){
	// Code to get data

}

(function(window,document,$,NSKWORKS){
	NSKWORKS.popgrowth = (function(){
		var country = $('#countries');
		var selected_country = '';

		var init_country_change = function(){
			country.on('change',function(){
				var that = $(this);
				NSKWORKS.setdata('selected_country',that.val());
				$.getJSON('/popgrowth/?data_type=json'+'&cc='+that.val(), function(data){
					populateTable(data);
					populateGraph(data);
					$('.poptbl-wrap h2 span').text($(':selected',that).text());
				});
			});	
		}
		

		var populateTable = function(data){
			var pop = data.population;
			var popStat = '',
				popTbl = $('.poptbl'),
				p;
				$('tbody',popTbl).remove();
				$(popTbl).append('<tbody></tbody>');
			for (var i = 0; i < pop.length; i++) {
				p = pop[i].population!=0 ? pop[i].population : 'Data not available!';
				popStat += '<tr><td>'+(i+1)+'</td><td>'+pop[i].year+'</td><td>'+p+'</td></tr>';
			};
			$('tbody',popTbl).append(popStat);
			$('.poptbl-wrap').show();
			$('.poptbl').hide();
		};

		var populateGraph = function(data){
			var pop = data.population,
				p,
				str = '';

			for (var i = 0; i < pop.length; i++) {
				if(pop[i].population!=0){
					str += pop[i].year+','+pop[i].population+"\n";
				}
			};

			 g = new Dygraph(
    			// containing div
    			document.getElementById("popgraph"),
			    // CSV or path to a CSV file.
			    "Year,Population(in million)\n" + str
  			);
		}

		var init_display_opt = function(){
			$('.display-opt a').on('click',function(e){
				e.preventDefault();
				var opt = $('span',this).hasClass('graph') ? 'graph' : 'table';
				if(opt=='graph'){
					$('#popgraph').show();
					$('.poptbl').hide();
					$('.span',this).removeClass('selected');
					$('.'+opt,this).addClass('selected');
				} else {
					$('.span',this).removeClass('selected');
					$('#popgraph').hide();
					$('.poptbl').show();
					$('.'+opt,this).addClass('selected');
				}
			});
		}

		var load_default_country = function(){
			// Code to fetch last selected country
			selected_country = 'IND';
			$.getJSON('/popgrowth/?data_type=json'+'&cc='+selected_country, function(data){
				populateTable(data);
				populateGraph(data);
				$('.poptbl-wrap h2 span').text($(':selected',that).text());
			});

		}

		var init = function(){
			init_country_change();
			init_display_opt();
			load_default_country();
		}

		return {
			init : init
		};
	}());
}(window,document,jQuery,NSKWORKS));

NSKWORKS.popgrowth.init();