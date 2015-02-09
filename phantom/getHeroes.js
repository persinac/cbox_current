var script = document.createElement('script');
script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js';
script.type = 'text/javascript';
document.getElementsByTagName('head')[0].appendChild(script);

var args = require('system').args;
args.forEach(function(arg, i) {
    console.log(i+'::'+arg);
});

var name = "";
if(args.length > 2) {
	console.log(args[2]);
	name = args[2];
} 

console.log("Started...");
var t_heroes = [];
var t_url = "http://www.crossfit.com/cf-info/faq.html#WOD1";


pjs.config({ 
	timeoutLimit: 1000,
	timeoutInterval: 1000,
	log: 'stdout',
	// options: 'json' or 'csv'
	format: 'json',
	// options: 'stdout' or 'file' (set in config.outFile)
	writer: 'file',
	outFile: 'cf_heroes.json'
});


pjs.addSuite({
	url:  t_url,
	//maxDepth: 2,
	loadScript: ['underscore.js'],
	ignoreDuplicates: true,    
	scrapers: [
		function() {
			var lines;
			var items = [];
			var header = $('h2');
			if(header.length > 0) {
				header.each(function(i, elem) {
					page = $(this).text().trim();
					var tables=$(this).next().find('tr');
					if(page.indexOf("4") > -1) {
						//console.log("PAGE: " + page + ", i: " + i);
						//console.log(page.length);
						var pageTwo = $(this).next().text();
						//console.log("THIS.NEXT: " + pageTwo + ", i: " + i);
						var begin = 0;
						var end = 0;
						var objCount = 0;
						var t_string = "";
						if(tables.length > 0) {
							tables.each(function(i, elem) {
								pageTwo = $(this).text().trim();
								if(pageTwo.indexOf("Hero") > -1 ) {
									begin = i;
								}
								if(pageTwo.indexOf("Men's Class Rankings") > -1) {
									end = i;
								}
								if(begin > 0 && end == 0) {
									lines = pageTwo.split(/\r\n|\r|\n/g);
																	
									for(var j = 0; j < lines.length; j++) {
										var t = 0;
										var hasLowerCase = false;
										
										if(lines[j].indexOf("Pull") > -1 || lines[j].indexOf("Run") > -1
											|| lines[j].indexOf("Kettlebell") > -1 || lines[j].indexOf("-ups") > -1
											|| lines[j].indexOf("GHD") > -1 || lines[j].indexOf("Squat") > -1 
											|| lines[j].indexOf("Row") > -1 || lines[j].indexOf("ascent") > -1
											|| lines[j].indexOf("Snatch") > -1 || lines[j].indexOf("Deadlift") > -1
											|| lines[j].indexOf("Burpee") > -1 || lines[j].indexOf("Wallball") > -1 
											|| lines[j].indexOf("Box jump") > -1 || lines[j].indexOf("Ring d") > -1
											|| lines[j].indexOf("unders") > -1 || lines[j].indexOf("extension") > -1
											|| lines[j].indexOf("Sit-ups") > -1 || lines[j].indexOf("elbows") > -1
											|| lines[j].indexOf("toes") > -1 || lines[j].indexOf("Overhead") > -1
											|| lines[j].indexOf("Turkish") > -1 || lines[j].indexOf("Swings") > -1
											|| lines[j].indexOf("Thrusters") > -1 || lines[j].indexOf("Rounds") > -1
											|| lines[j].indexOf("rounds") > -1 || lines[j].indexOf("sit-") > -1
											|| lines[j].indexOf("rope climbs") > -1
											|| lines[j].indexOf("pull") > -1 || lines[j].indexOf("power") > -1 
											|| lines[j].indexOf("Power") > -1 || lines[j].indexOf("Muscle-") > -1
											|| lines[j].indexOf("Clean") > -1 || lines[j].indexOf("clean") > -1
											|| lines[j].indexOf("pound") > -1 || lines[j].indexOf("Pound") > -1
											|| lines[j].indexOf("pounds") > -1 || lines[j].indexOf("Pounds") > -1
											|| lines[j].indexOf("Handstand") > -1 || lines[j].indexOf("handstand") > -1
											|| lines[j].indexOf("dips") > -1 || lines[j].indexOf("overhead") > -1
											|| lines[j].indexOf("minutes ") > -1 || lines[j].indexOf("completed") > -1
											|| lines[j].indexOf("reps") > -1) {
												if(lines[j].indexOf("survived") > -1 || lines[j].indexOf("Survived") > -1) {
												//do nothing
												} else {
													console.log("FOUND KEYWORD!!!!");
													console.log(lines[j]);
													t_string += lines[j].trim() + ", ";
													console.log("CURRENT ITEM: " + items[objCount-1].name);
													items[objCount-1].workout = t_string;
												}
										} else if(lines[j].length > 1) {
											console.log("INDEX: "+j+"\nLINE: \n" + lines[j].trim());
											while (t <= lines[j].trim().length && hasLowerCase == false){
												character = lines[j].trim().charAt(t);
												if (!isNaN(character * 1)) {
													//console.log('character is numeric');
													//console.log("CHARACTER ["+t+"]: "+ character);
													//hasLowerCase = true;
												} else {
													if (character == character.toUpperCase()) {
														//console.log('upper case true');
													}
													if (character == character.toLowerCase()){
														//console.log('lower case true');
														hasLowerCase = true;
													}
												}
												t++;
											}
											if(hasLowerCase == false) {
												//console.log("INDEX: "+j+"\nLINE: \n" + lines[j]);
												var f = new Object();
												//console.log("t-String: "+ t_string);
												f.name = lines[j];
												//f.workout = t_string;
												items.push(f);
												objCount++;
												t_string ="";
											}
										}
									}
								}
							});
						}
					}
				});
			}
			/*
			
			}*/
			var max_page = 0;
			var len = items.length;

			for(var p = 0; p < items.length; p++) {
				console.log("ITEMS at "+p+": "+items[p].name+"\nWOD: "+ items[p].workout);
			}
			return items;
		} 
	]
});
