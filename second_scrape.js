console.log("Started...");

pjs.config({ 
    timeoutLimit: 1000,
    timeoutInterval: 1000,
    maxDepth: 1,
        // options: 'stdout', 'file' (set in config.logFile) or 'none'
    log: 'stdout',
    // options: 'json' or 'csv'
    format: 'csv',
    // options: 'stdout' or 'file' (set in config.outFile)
    writer: 'file',
    outFile: 'output.csv'
});

pjs.addSuite({
  url: 'http://cboxbeta.com/contactform',
  maxDepth: 1,
  loadScript: ['jquery-1.11.1.min.js'],
  ignoreDuplicates: true,
  moreUrls: function() {
    var urls = _pjs.getAnchorUrls('a', false);
    var result = _.filter(urls, function (url) {
      return url.indexOf('javascript') == -1
    });
    console.log("Found " + urls.length + " urls. Using " + result.length)
    return result
  },
  scraper: function() {
    var result = []
    var links = $('a')
    links = links.each(function(index, elem) { 
		console.log($(elem).text());
      return $(elem).text()
    })
    //result = result.concat(links)
    //return result
  }
});