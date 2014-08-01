(function(window,undefined){

module("divjs");

test("Test `Math.div`",function(){
	equal( 0, Math.div(1,3), "tow operands are all numbers.");
	equal( 1, Math.div(5,"3"), "2nd opnd is string of number.");
	equal( 2, Math.div("8",3), "1st opnd is string of number.");
	try{
		Math.div('a','b');
	}catch(e){
		ok(true, 'throws exception if either operands cannot treat as number.');
	}
});

test("Test `Math.mod`",function(){
	equal( 1, Math.mod(1,3), "tow operands are all numbers.");
	equal( 2, Math.mod(5,"3"), "2nd opnd is string of number.");
	equal( 2, Math.mod("8",3), "1st opnd is string of number.");
	try{
		Math.mod('a','b');
	}catch(e){
		ok(true, 'throws exception if either operands cannot treat as number.');
	}
});

test("Test `Number#div`",function(){
	var one = 1;
	var five = "5";
	var thirteen = 13;
	var three = "three";
	equal( 0, one.div(3), "operand is number.");
	equal( 2, thirteen.div(five), "operand is string of number.");
	try{
		one.div("b");
	}catch(e){
		ok(true, 'throws exception if operand cannot treats as number.');
	}

	try{
		three.div(1);
	}catch(e){
		ok(true, 'non-number object cannot call `div` method.');
	}
});

test("Test `Number#mod`",function(){
	var one = 1;
	var five = "5";
	var thirteen = 13;
	var three = "three";
	equal( 1, one.mod(3), "operand is number.");
	equal( 3, thirteen.mod(five), "operand is string of number.");
	try{
		one.mod("b");
	}catch(e){
		ok(true, 'throws exception if operand cannot treats as number.');
	}

	try{
		three.mod(1);
	}catch(e){
		ok(true, 'non-number object cannot call `div` method.');
	}
});

}( (function(){return this;}.call()) ) );