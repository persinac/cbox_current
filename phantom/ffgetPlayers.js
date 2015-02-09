var script = document.createElement('script');
script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var args = require('system').args;
args.forEach(function(arg, i) {
    console.log(i+'::'+arg);
});
var category_id = 2;
var fileName = "";
if(args.length > 2) {
	console.log(args[2] + " " + args[3]);
	category_id = args[2];
	fileName = args[3];
} 
console.log("Started...");
var t_url = []
var url = "";

for(var i = 0; i < 61; i+=15) {
	url = "http://games.espn.go.com/ffl/tools/projections?display=alt&slotCategoryId="+category_id+"&startIndex="+i+"";
	console.log(url);
	t_url.push(url);
}

pjs.config({ 
	timeoutLimit: 1000,
	timeoutInterval: 1000,
	log: 'stdout',
	// options: 'json' or 'csv'
	format: 'csv',
	// options: 'stdout' or 'file' (set in config.outFile)
	writer: 'file',
	outFile: fileName + ".csv"
});


pjs.addSuite({
	url:  t_url,
	loadScript: ['underscore.js'],
	ignoreDuplicates: true,    
	scrapers: [
		function() { 
			var counter = 0;
			var page = "";
			var items = [];
			var playerNames = $('td .subheadPlayerNameLink');
			//console.log(playerNames);
			playerNames.each(function(i, elem) {
				//console.log($(this).text());
				playerName = $(this).text().trim();
				var f = new Object();
				f.name = playerName;
				items.push(f);
			});
			var max_page = 0;
			var len = items.length;
			for(var p = 0; p < items.length; p++) {
				console.log("ITEMS PAGE at "+p+": "+items[p].name);
			}
			return items;
		} 
	]
});

