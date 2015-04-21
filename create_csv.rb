require 'tsv'
require 'csv'

def generate_csv data_array, csv_path
	CSV.open(csv_path, "w") do |csv|
		data_array.each do |data_row|
		  csv << data_row
		end
	end
end

def find_type type
	if ((type == "image") || (type == "video") || (type == "audio"))
		return type
	else
		puts "Assuming type as text"
		return "text"
	end
end

def get_tsv_table content
	str = "<table>"
	content.each_line do |line|
		str += "<tr>"
		items = line.split("\t")
		items.each do |item|
			str += "<td>#{item}</td>"
		end
		str += "</tr>"
	end
	str += "</table>"
end

def get_data type, asset_file, base_path
	if (type == "text")
		content = File.read("#{base_path}/assets/#{asset_file}")
		content = get_tsv_table content
	else
		puts "Other scenarios like image, video and audio is not handled"
	end
	return content
end

def get_answer_type type
	if type == "multiple choice"
		return "select"
	else
		puts "None of the other case except multiple choice has been handled in the answer type"
	end
end

def get_answer_data answer_type, possible_answers
	if answer_type == "select"
		return possible_answers
	else
		puts "None of the other case except select has been handled in the answer data"
	end	
end



data_array = []
start_id = 641
base_path = "/Users/vikasyaligar/Sites/crowds/data/tasks/historical_events_II"
domain_id = 42
title = "Select the option representing the correct chronological order of the historical events."
csv_path = "tasks_output.csv"
tsv_path = "/Users/vikasyaligar/Sites/crowds/data/tasks/historical_events_II/tasks.tsv"

TSV.parse_file(tsv_path).with_header.each do |f|
	type = find_type(f["asset_type"])
	data = get_data(type, f["asset_file"], base_path)
	answer_type = get_answer_type(f["answer_type"])
	answer_data = get_answer_data(answer_type, f["possible_answers"])
	data_array << [start_id, domain_id, title, type, data, answer_type, answer_data, f["correct_answer"]]
	start_id += 1
end

generate_csv data_array, csv_path
