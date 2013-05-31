var fs = require("fs");
var path = require("path");
var red   = '\033[31m',
    blue  = '\033[34m',
    bold = '\033[1m',
    reset = '\033[0m';

function readFile(path) {
  return fs.readFileSync(path);
}

function writeFile(path, data, offset) {
  if (writeFile.filesInfo.indexOf(path) === -1)
  {
    fs.truncateSync(path);
    writeFile.filesInfo.push(path);
  }
  offset = offset || 0;
  data = offset ? data.slice(offset) : data;
  fs.appendFileSync(path, data);
}
writeFile.filesInfo = [];


var pattern = /["']require ([a-zA-Z0-9\._-]*)["']\n/g;
var depedencies_added = [];

var toCompile = process.argv[2];
var dirname = path.dirname(toCompile);
var target_path = process.argv[3] || path.join(dirname, "out.js");

function find_depedencies(file) {
  var found = "",
      fileContent = readFile(file),
      dirname = path.dirname(file),
      depedencies = [],
      index = 0;

  while (found = pattern.exec(fileContent))
  {
    index = found['index'] + found[0].length ;
    depedencies.push(path.join(dirname, found[1]), index);
  }
  return depedencies;
}

function file_header(file)
{
  return "\n//***************" + file + "***************//\n";
}

function build(file, out_path, depth)
{
  depth = depth || 1;
  if (fs.existsSync(file))
  {
    console.log(Array(depth).join("    "), "->processing ", bold, file, reset);
    var fileContent = readFile(file),
        depedencies = find_depedencies(file),
        current = "",
        index = 0,
        i = 0,
        len = depedencies.length;
    depth += 1;
    for (; i < len; i+= 2) {
      current = depedencies[i];
      index = depedencies[i+1];
      if (depedencies_added.indexOf(current) === -1) {
        build(current + ".js", out_path, depth);
        depedencies_added.push(current);
      }
    }
    writeFile(out_path, file_header(file))
    writeFile(out_path, fileContent, index);
  } else 
  {
    console.log(file, "does not exist, skipping...");
  }
}

build(toCompile, target_path);









