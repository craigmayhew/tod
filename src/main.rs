let codeStart = "extern crate docopt;
#[macro_use]
extern crate serde_derive;

use docopt::Docopt;

const USAGE: &'static str = \"
TOD generated application.
Usage:
  todapp <arg1>
Options:
  -h --help         Show this screen.
  --version         Show version.
\";

#[derive(Debug, Deserialize)]
struct Args {
    arg_1: Option<i32>,
}

//parse command line arguements/switches
fn parse_config() -> (docopt::ArgvMap) {
	Docopt::new(USAGE)
		.and_then(|dopt| dopt.parse())
		.unwrap_or_else(|e| e.exit())
}

fn main() -> std::io::Result<()> {
  let args: docopt::ArgvMap = parse_config();

	";

//generate a /tmp/src/main.rs
fn main() -> std::io::Result<()> {
  generate();
  Ok(())
}

fn generate() -> std::io::Result<()> {
  
  codeStart + 
  ///////////////////
  /// todo: have some loops here or some clevor logic to build these lines
  "let var1:i32 = function(args.get_str(\"<arg1>\").parse().unwrap());
  let var2:i32 = function2(var1);

  let answer:i32 = function(args.get_str(\"<arg1>\").parse().unwrap());
  
  println!(\"{}{}\",\"Triple: \", answer.to_string());"
  ////////////
  "
  
  println!(\"{}{}\",\"Answer: \", answer.to_string());
  Ok(())
}
"
  //todo: append the number of functions to the file, and rename as needed
  //todo: write main.rs to /tmp/src/main.rs

  println!("{}{}","Triple: ", answer.to_string());
  Ok(())
}