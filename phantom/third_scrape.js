var script = document.createElement('script');
script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var args = require('system').args;
args.forEach(function(arg, i) {
    console.log(i+'::'+arg);
});
var year = 11;
if(args.length > 2) {
	console.log(args[2]);
	year = args[2];
} 
console.log("Started...");
var t_url = []
var url = "";
var divi = 0;

if(year < 13) {
	divi = 12;
} else {
	divi = 14
}
for(var reg = 1; reg < 18; reg++) {
	for(var d = 1; d < divi; d++) {
		url = "http://games.crossfit.com/scores/leaderboard.php?stage=5&sort=0&page="+1+"&division="+d+"&region="+reg+"&numberperpage=60&competition=0&frontpage=0&expanded=0&year="+year+"&full=1&showtoggles=0&hidedropdowns=1&showathleteac=1&=&is_mobile=0";
		t_url.push(url);
	}
}

pjs.config({ 
	timeoutLimit: 1000,
	timeoutInterval: 1000,
	log: 'stdout',
	// options: 'json' or 'csv'
	format: 'json',
	// options: 'stdout' or 'file' (set in config.outFile)
	writer: 'file',
	outFile: year+'_cfldrbrd_pages.json'
});


pjs.addSuite({
	url:  t_url,
	//maxDepth: 2,
	loadScript: ['underscore.js'],
	ignoreDuplicates: true,    
	scrapers: [
		function() { 
			var counter = 0;
			var page = "";
			var items = [];
			var region = $('#edit-region option:selected');
			var division =  $('#edit-division option:selected');
			var links=$('#leaderboard-pager a');
			var pageFinder = $('#leaderboard td.name');
			if(pageFinder.text().trim().length > 0) {
				if(links.length > 0) {
					links.each(function(i, elem) {
						page = $(this).text().trim();
						var f = new Object();
						f.region = region.text().trim();
						f.division = division.text().trim();
						f.page = page;
						items.push(f);
					});
				} else {
					page = 1;
					var f = new Object();
					f.region = region.text().trim();
					f.division = division.text().trim();
					f.page = page;
					items.push(f);
				}
			} else {
				var f = new Object();
				f.region = region.text().trim();
				f.division = division.text().trim();
				f.page = "-";
				items.push(f);
			}
			var max_page = 0;
			var len = items.length;
					
			if(items.length > 1) {
				console.log("PRE-SPLICE");
				console.log("ITEMS LENGTH: "+items.length);
				console.log("ITEMS PAGE: "+items[0].page);
				items.splice(1, len-1);
				console.log("POST-SPLICE");
				console.log("ITEMS LENGTH: "+items.length);
				console.log("ITEMS PAGE: "+items[0].page);
			}
			for(var p = 0; p < items.length; p++) {
				console.log("ITEMS PAGE at "+p+": "+items[p].page);
			}
			return items;
		} 
	]
});

