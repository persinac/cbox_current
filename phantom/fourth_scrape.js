
function runMe(t_year, s_url) {
	console.log("T-YEAR: " + t_year + ", s_url: " + s_url);
	pjs.config({ 
		timeoutLimit: 1000,
		timeoutInterval: 1000,
		maxDepth: 1,
			// options: 'stdout', 'file' (set in config.logFile) or 'none'
		log: 'stdout',
		// options: 'json' or 'csv'
		format: 'json',
		// options: 'stdout' or 'file' (set in config.outFile)
		writer: 'file',
		outFile: t_year+'_cfldrbrd_output.json'
	});
	
	pjs.addSuite({
		url:  s_url,
		maxDepth: 2,
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
				var links=$('td');
				var t_name = "";
				var first_name_on_page = "a";
				//console.log("Links: "+links.toString());
				links.each(function(i, elem) {
					if(i == "1") {
						
						//console.log("First IF Statement, SHOULD = 1? " + i);
						//console.log("First name: " + first_name_on_page);
						//console.log("TEXT: " + $(this).text().trim());
						if(first_name_on_page==="a") {
							//first_name_on_page = $(this).text().trim()
						} else if(first_name_on_page == $(this).text().trim()){
							//console.log("\n******REDUNDANT DATA******\n");
						}
					}
					
					if($(this).text().trim().length > 1) {
						if(counter === 0) {
							place = $(this).text().trim();
							counter++;
						} else if(counter === 1) {
							name = $(this).text().trim();
							counter++;
						} else if(counter === 2) {
							wodOneScore = $(this).text().trim();
							counter++;
						} else if(counter === 3) {
							wodTwoScore = $(this).text().trim();
							counter++;
						} else if(counter === 4) {
							wodThreeScore = $(this).text().trim();
							counter++;
						} else if(counter === 5) {
							wodFourScore = $(this).text().trim();
							counter++;
						} else if(counter === 6) {
							wodFiveScore = $(this).text().trim();
							var f = new Object();
							f.place = place;
							f.name = name;
							f.wOneScore = wodOneScore;
							f.wTwoScore = wodTwoScore;
							f.wThreeScore = wodThreeScore;
							f.wFourScore = wodFourScore;
							f.wFiveScore = wodFiveScore;
							items.push(f);
							counter = 0;
						}
					}
					/*
					f.price=$(this).find(".variant-final-price").text().trim();
					f.text=$(this).find(".variant-title a").text().trim();
					f.url="http://www.xyzsales.com"+ $(this).find(".variant-title a").attr("href").trim();
					*/
				});
				return items;
				
			}
		]
	});
}