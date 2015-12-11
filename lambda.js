exports.handler = function(event, context) {

  var vm = require('vm');

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
    var script = new vm.Script(js, {timeout: 5});
    script.runInNewContext(context);
    return context.output;
  };

  var rand = function (low,high){
    return Math.floor((Math.random() * high) + low);
  }

  //options:
  var sand = {output:null,i:event.input};
  var desiredOutput = event.desiredOutput;

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

    try{
      var test = Unit(js,sand);
      //console.log('\nJS: '+js+'\n OUTPUT: '+test+'\n\n')
      //console.log(desiredOutput);
      if(desiredOutput == test){
        context.succeed({success:'\nJS: '+js+'\n OUTPUT: '+test+'\n\n'});
      }
    } catch(err){
      if(err){
        //the code errored so its useless
        //response.write(err.toString());
      }
    }
  }
  context.done();
}
