//required for accepting neat command line arguements
extern crate docopt;
//required by decopt
#[macro_use]
extern crate serde_derive;

use docopt::Docopt;

const USAGE: &'static str = "
TOD generated application.
Usage:
  todapp <arg1>
Options:
  -h --help         Show this screen.
  --version         Show version.
";

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

	//INSERT_MAIN_CODE_HERE//
  
  println!("{}{}","Answer: ", answer.to_string());
  Ok(())
}