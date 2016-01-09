var util = require('util');
var vm = require('vm');

var http = require('http');

var lineParts = [
  '=',
  '==',
  '===',
  '!=',
  '!==',
  '+',
  '-'
];
var wholeLines = [
  'return i;',
  'i++;',
  'i--;',
  'for(i=0;i<10;i++){',
    
  '}'
];
var lineStart = [
  '(',
  'foreach(',
  'i = ' 
];
var lineEnd = [
  ')}',
  ')',
  '}',
  'i;'
];

var Unit = function (js, sandbox) {
  var context = new vm.createContext(sandbox);
  var script = new vm.Script(js, {timeout: 10});
  script.runInNewContext(context);
  return context.output;
};

var rand = function (low,high){
  return Math.floor((Math.random() * high) + low);
}

http.createServer(function (request, response) {
  response.writeHead(200, {'Content-Type': 'text/plain'});
  
  //options:
  var sand = {output:null,i:0};
  var desiredOutput = 8;

  for(var i2=0;i2<50;i2++){
    var js = [];
    for(var i=1;i<10;i++){
      //line start
      if(rand(1,10)===1){
        js.push(lineStart[rand(0,2)]);
      }

      //whole line
      if(rand(1,3)===1){
        js.push(wholeLines[rand(0,4)]);
      }

      //line end
      if(rand(1,10)===1){
        js.push(lineEnd[rand(0,3)]);
      }
    }
    if(js.length === 0){
      continue;
    }
    var splice = js.splice(0,rand(0,js.length-1));
    splice.push(' output = ');
    js = splice.concat(js.splice(splice.length-1,rand(0,js.length-1)));
    js = js.join('');
    
    response.write('.');
    try{
      var test = Unit(js,sand);

      if(desiredOutput == test){

        response.write('\nJS: '+js+'\n OUTPUT: '+test+'\n\n');
      }
    }
    catch(err){
      if(err){
        //the code errored so its useless
        //response.write(err.toString());
      }
    }
  }
  
  response.end('\n\nAttempts: '+i2);
}).listen(8124);

console.log('Server running at http://127.0.0.1:8124/');
