// Script to process all the less files and convert them to CSS files
// Run from themes/default/css/manager like:
//
//	$ node compile.js

var fs = require('fs'),		// file system access
	path = require('path'),	// get directory from file name
	less = require('C:/less/lib/less');	// less processor

var options = {
	compress: false,
	optimization: 1,
	silent: false
};

var allFiles = [].concat(
		fs.readdirSync(".")
	),
	lessFiles = allFiles.filter(function(name){ return name && name == "manager.less"; });

lessFiles.forEach(function(fname){
	console.log("=== " + fname);
	fs.readFile(fname, 'utf-8', function(e, data){
		if(e){
			console.error("lessc: " + e.message);
			process.exit(1);
		}

		new(less.Parser)({
			paths: [path.dirname(fname)],
			optimization: options.optimization,
			filename: fname
		}).parse(data, function(err, tree){
			if(err){
				less.writeError(err, options);
				process.exit(1);
			}else{
				try{
					var css = tree.toCSS({ compress: options.compress }),
						outputFname = fname.replace('.less', '.css'),
						outputFnameDev = fname.replace('.less', '.dev.css');
					var fd = fs.openSync(outputFname, "w");
					fs.writeSync(fd, css, 0, "utf8");
					fd = fs.openSync(outputFnameDev, "w");
					fs.writeSync(fd, css, 0, "utf8");
				}catch(e){
					less.writeError(e, options);
					process.exit(2);
				}
			}
		});
	});
});
