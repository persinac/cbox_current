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

var divi = 0;
var t_url = []
var url = "";
//var year = "0";

console.log("Loading .json page file");
var jsonFile = require('C:\\leaderboard_output\\'+year+'_cfldrbrd_pages.json');
var item_counter = 0
var page = "";

if(year < 13) {
	divi = 12;
} else {
	divi = 14
}

for(var reg = 1; reg < 18; reg++) {
	for(var d = 1; d < divi; d++) {
		page = jsonFile[item_counter].page;
		if(page == "-") {
			url = "http://games.crossfit.com/scores/leaderboard.php?stage=5&sort=0&page="+1+"&division="+d+"&region="+reg+"&numberperpage=60&competition=0&frontpage=0&expanded=0&year="+year+"&full=1&showtoggles=0&hidedropdowns=1&showathleteac=1&=&is_mobile=0";
			t_url.push(url);
		} else {
			var act_page = parseInt(page);
			for(var i = 1; i < act_page+1; i++) {
				url = "http://games.crossfit.com/scores/leaderboard.php?stage=5&sort=0&page="+i+"&division="+d+"&region="+reg+"&numberperpage=60&competition=0&frontpage=0&expanded=0&year="+year+"&full=1&showtoggles=0&hidedropdowns=1&showathleteac=1&=&is_mobile=0";
				t_url.push(url);
			}
		}
		item_counter++;		
	}
}

pjs.config({ 
	timeoutLimit: 1000,
	timeoutInterval: 1000,
	log: 'stdout',
	// options: 'json' or 'csv'
	format: 'csv',
	// options: 'stdout' or 'file' (set in config.outFile)
	writer: 'file',
	outFile: year+'_cfldrbrd_output.csv'
});


pjs.addSuite({
	url:  t_url,
	loadScript: ['underscore.js'],
	ignoreDuplicates: true,    
	scrapers: [
		function() { 
			var counter = 0;
			var place = "";
			var name = "";
			var wodOneScore = "";
			var wodTwoScore ="";
			var wodThreeScore = "";
			var wodFourScore = "";
			var wodFiveScore = "";
			var items = [];
			var region = $('#edit-region option:selected');
			console.log("REGION: " + region.text().trim());
			var division =  $('#edit-division option:selected');
			console.log("DIVISION: " + division.text().trim());
			var links=$('td');
			var t_name = "";
			var first_name_on_page = "a";
			var pageFinder = $('#leaderboard td.name');
			var t_index = 0;
			if(pageFinder.text().trim().length > 0) {
				if(links.length > 0) {
					links.each(function(i, elem) {
						if($(this).text().trim().length > 1) {
							if(counter === 0) {
								place = $(this).text().trim();
								counter++;
							} else if(counter === 1) {
								name = $(this).text().trim();
								counter++;
							} else if(counter === 2) {
								wodOneScore = $(this).text().trim();
								t_index = wodOneScore.indexOf(")");
								wodOneScore = wodOneScore.substring(0,t_index+1);
								counter++;
							} else if(counter === 3) {
								wodTwoScore = $(this).text().trim();
								t_index = wodTwoScore.indexOf(")");
								wodTwoScore = wodTwoScore.substring(0,t_index+1);
								counter++;
							} else if(counter === 4) {
								wodThreeScore = $(this).text().trim();
								t_index = wodThreeScore.indexOf(")");
								wodThreeScore = wodThreeScore.substring(0,t_index+1);
								counter++;
							} else if(counter === 5) {
								wodFourScore = $(this).text().trim();
								t_index = wodFourScore.indexOf(")");
								wodFourScore = wodFourScore.substring(0,t_index+1);
								counter++;
							} else if(counter === 6) {
								wodFiveScore = $(this).text().trim();
								t_index = wodFiveScore.indexOf(")");
								wodFiveScore = wodFiveScore.substring(0,t_index+1);
								var f = new Object();
								f.place = place;
								f.name = name;
								f.region = region.text().trim();
								f.division =  division.text().trim();
								f.wOneScore = wodOneScore;
								f.wTwoScore = wodTwoScore;
								f.wThreeScore = wodThreeScore;
								f.wFourScore = wodFourScore;
								f.wFiveScore = wodFiveScore;
								items.push(f);
								counter = 0;
							}
						}
					});
					
				} else {
					console.log("LINKS LENGTH: " + links.length);
				}
			} else {
				var f = new Object();
				f.place = place;
				f.name = "-";
				f.region = region.text().trim();
				f.division = division.text().trim();
				f.wOneScore = "-";
				f.wTwoScore = "-";
				f.wThreeScore = "-";
				f.wFourScore = "-";
				f.wFiveScore = "-";
				items.push(f);
			}
			return items;
		} 
	]
});


