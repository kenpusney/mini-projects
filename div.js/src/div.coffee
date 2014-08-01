root = global ? window

root.Number::div = (divisor) -> 
	if Number(divisor)
		Math.round ( this / divisor - 0.5 )
	else
		throw "Exception: unknown divisor."

root.Number::mod = (moderator) ->
	if Number(moderator)
		Math.floor(this) % Math.floor(moderator)
	else
		throw "Exception: unknown divisor."

root.Math.div = (a, b) ->
	if Number(a) && Number(b)
		Number(a).div(b)
	else
		throw "Exception: unknown arguments type."

root.Math.mod = (a, b) ->
	if Number(a) && Number(b)
		Number(a).mod(b)
	else
		throw "Exception: unknown arguments type."
class DivJS
	version: "0.1.0"

root.divjs = new DivJS()

if exports?
	exports.divjs = new DivJS()