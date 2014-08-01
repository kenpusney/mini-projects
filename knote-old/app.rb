
require 'sinatra'
require 'sqlite3'

before do
	@db = SQLite3::Database.new("db/note.db")
    @db.results_as_hash = true
end

get '/' do
    "Hello,world"
end

get '/note/:id' do
	id = params[:id]
	if not (/^[0-9]+$/ =~ id)
		halt 404
	end
	result = @db.query("select * from note 
						where id = #{id};")
	@note = {}
	@tags = []
	result.each{ |row|
		@note = row 
	}
	if @note == {} or @note == nil
		halt 404
	end
	result = @db.query("select tag_id from note_tag 
						where note_id = #{@note['id']};")
	result.each{ |row|
		id = row['tag_id']
		id_result = @db.query("select * from tag
						where id = #{id};")
		id_result.each{ |tag_row|
			@tags << tag_row
		}
	}	
	erb '/note'.to_sym
	
end

get '/tag/:uri' do
	uri = params[:uri]
	if (/^[0-9]+$/ =~ uri)
		halt 404
	end
	result = @db.query("select * from tag
						where uri = '#{uri}';")
	@tag = {}
	@notes = []
	result.each{ |row|
		@tag = row
	}
	if @tag == {} or @tag == nil
		halt 404
	end
	result = @db.query("select note_id from note_tag
						where tag_id = #{@tag['id']};")
	result.each{ |row|
		id = row['note_id']
		id_result = @db.query("select * from note
						where id = #{id};")
		id_result.each { |note_row|
			@notes << note_row
		}
	}
	
	erb '/tag'.to_sym
end
get '/note' do
    @notes = @db.query("select * from note;")
    erb '/note_list'.to_sym
end

get '/tag' do
    @tags = @db.query("select * from tag;")
    erb '/tag_list'.to_sym
end

