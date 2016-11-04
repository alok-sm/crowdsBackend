app.controller('aboutTeamController', function($scope) {
    $scope.webTeam = segment(getWebTeam(), 3);
    $scope.studentResearchers = segment(getStudentResearchers(), 3);
    $scope.researchDirectors = segment(getResearchDirectors(), 3);

    function getResearchDirectors() {
        var team = [
            {
                name: "Sharad Goel",
                school: "Stanford University, California",
                bio: "Sharad is an Assistant Professor at Stanford in the Management Science & Engineering Department. His primary area of research is computational social science, an emerging discipline at the intersection of computer science, statistics, and the social sciences."
            },
            {
                name: "Camelia Simoiu",
                school: "Stanford University, California",
                bio: "Camelia is a PhD student in the Management Science & Engineering Department at Stanford, with a background in economics, statistics, and network analysis. Her research interests are in complex social systems with the goal to understand and better design socio-economic policies."
            },
            {
                name: "Imanol Arrieta",
                school: "Stanford University, California",
                bio: "Imanol is a PhD student in the Management Science & Engineering Department at Stanford, with a background in economics and applied mathematics. His research interests are in Artificial Intelligence and Computational Sustainability."
            }
        ];
        return team;
    }

    function getWebTeam() {
        var team = [];
        team.push({
            name: "Alok Shankar Mysore",
            school: "PES Institute of Technology, Bengaluru",
            bio: "Alok is a final year undergraduate student at PESIT Bangalore majoring in Computer Science. His research interests include Data Science, Information Systems and Machine Learning. He worked as a intern at Bing, Microsoft in improving search results using open data. He also participates in many Hackathons and competitve programming events. Automobiles and World history are his other interests.",
            site: "http://aloksm.com"
        });

        team.push({
            name: "Chiraag Sumanth",
            school: "PES Institute of Technology, Bengaluru",
            bio: "Chiraag is a final year undergraduate student pursuing a Bachelor's degree in Computer Science. As an avid tech enthusiast, he love participating in hackathons and programing competitions. His research interests include Machine Learning, Natural Language Processing, and Data Science. His other interests include numismatics and automobiles.",
            site: "http://chiraagsumanth.github.io"
        });

        team.push({
            name: "Ramesh Arvind",
            school: "PES Institute of Technology, Bengaluru",
            bio: "I am final year engineering student at PESIT pursuing Computer Science. My areas of interests include Web design, Mobile application development and Computer Security. My other interests include gaming and music.",
            site: undefined
        });

        team.push({
            name: "Bhargav HS",
            school: "PES Institute of Technology, Bengaluru",
            bio: "I am an undergraduate student pursuing a bachelor's degree in Engineering with Computer Sciences as major from PES Institute of Technology, Bengaluru. My interests include Data Analytics, Data Visualization and Cloud Computing. I have worked as a Visiting Researcher at Dalhousie University, NS in the field of Data Management. I am fluent in Python (with a gold badge on Stack Overflow), R, C and Java. I love travelling, singing and am interested in histories of the ancient world.",
            site: "http://bhargav-rao.github.io/"
        });

        team.push({
            name: "Arvind Srikantan",
            school: "PES Institute of Technology, Bengaluru",
            bio: "I am an undergraduate student majoring in Computer Science at PESIT, Bangalore. I am passionate about developing various kinds of applications and reading about the latest technological developments and gadgets. I have participated in several Hackathons conducted in Bangalore. My academic interests include Machine Learning, Natural Language Processing and Compression techniques. Apart from this, I am an avid biker,a foodie and enjoy cooking a variety of cuisines.",
            site: undefined
        });

        team.push({
            name: "Vikas S Yaligar",
            school: "National Institute of Technology Karnataka, Surathkal",
            bio: "Vikas Yaligar, majored in Information Technology from National Institute of Technology (NITK). While he was at NITK, he volunteered at MediaWiki (Google Summer of Code student) and Samsung R&D (Student Trainee). He also helped a few startups build their products. He was instrumental in building the first version of the product for TongueStun Food Network, a food technology startup in Bangalore. He is an avid contributor to Open Source projects, and takes a lot of interest in Web Security, Web & Mobile Networks, IOT, Performance testing, HCI & WOC.",
            site: undefined
        });

        team.push({
            name: "Atif Ahmed",
            school: "National Institute of Technology Karnataka, Surathkal",
            bio: "I am a B.Tech (Bachelor of Technology) student at National Institute of Technology Karnataka, Surathkal currently in my final year. My areas of interest include Distributed Systems and Data Mining. I am always fascinated by optimization problems. I always aspire to learn more and more and to use my knowledge and skills to solve real world problems.",
            site: undefined
        });


        team.push({
            name: "Mani Shankar",
            school: "National Institute of Technology Karnataka, Surathkal",
            bio: "I completed my Bachelor of Technology in Information Technology from 2015 batch of National Institute of Technology Karnataka, Surathkal. I am currently working in Oracle India Development Center - Bangalore. I am interested in Machine Learning, Computational Neuroscience, Mathematics and MOOCs. I am fond of science fiction, YouTube videos and good music",
            site: undefined
        });

        team.push({
            name: "Mayank Pahadia",
            school: "National Institute of Technology Karnataka, Surathkal",
            bio: "I am a final year student at National Institute of Technology Karnataka, Surathkal. My research interests include Data Mining and Bioinformatics. My aim is to use the information present in our DNAs, to solve the disease prediction problem.",
            site: undefined
        });

        team.push({
            name: "Tushar Dobhal",
            school: "National Institute of Technology Karnataka, Surathkal",
            bio: "I am a final year student at National Institute of Technology Karnataka, Surathkal. My research interests include Computer Vision, Image Processing and Machine Learning. My ambition in life is to replicate and go beyond human vision capabilities to develop applications and products that are cost-effective and practicable. ",
            site: undefined
        });

        return team;
    }


    function getStudentResearchers() {
        var team = [];
        team.push({name:"Aayush Attri", school:"BITS Pilani Goa Campus"});
        team.push({name:"Aditya Singh", school:"International Institute of Information Technology, Hyderabad"});
        team.push({name:"Akshansh", school:"Maharaja Agrasen institute of Technology"});
        team.push({name:"Anjali Singh", school:"Indian Institute of Technology, Delhi"});
        team.push({name:"Arpita Chandra", school:"International Institute of Information Technology, Hyderabad"});
        team.push({name:"Ashrith Sheshan", school:"BMS College of Engineering"});
        team.push({name:"Avinash Paritala", school:"Guru Nanak Engineering College"});
        team.push({name:"Bharat Munshi", school:"International Institute of Information Technology, Hyderabad"});
        team.push({name:"Bipin Thomas", school:"College of Engineering, Chengannur"});
        team.push({name:"Glincy Mary  Jacob", school:"College of Engineering, Chengannur"});
        team.push({name:"Harsh Parikh", school:"Indian Institute of Technology, Delhi"});
        team.push({name:"Himani Agarwal", school:"Jaypee Institute Of Information Technology, Noida"});
        team.push({name:"Ishan Yelurwar", school:"BITS Pilani Goa Campus"});
        team.push({name:"Kasyap Varma-Dattada", school:"BITS Pilani Hyderabad Campus"});
        team.push({name:"Lokesh Tuteja", school:"Maharaja Agrasen Institute of Technology"});
        team.push({name:"Mandar Pradhan", school:"BITS Pilani Goa Campus"});
        team.push({name:"Paras Gupta", school:"Jaypee Institute Of Information Technology, Noida"});
        team.push({name:"Prashant Sinha", school:"Cluster Innovation Centre"});
        team.push({name:"Praveen Kumar-Kolla", school:"Indian Institute of Technology, Guwahati"});
        team.push({name:"Pulkit Verma", school:"Indian Institute of Technology, Guwahati"});
        team.push({name:"Rachit Madan", school:"Indian Institute of Technology, Delhi"});
        team.push({name:"Rahul Phatak", school:"BITS Pilani Goa Campus"});
        team.push({name:"Rajat Agarwal", school:"BITS Pilani Goa Campus"});
        team.push({name:"Sahil Loomba", school:"Indian Institute of Technology, Delhi"});
        team.push({name:"Sai Anirudh-Kondaveeti", school:"Indian Institute of Technology, Guwahati"});
        team.push({name:"Sameeksha Khillan", school:"Indian Institute of Technology, Delhi"});
        team.push({name:"Sandeep Konam", school:"Rajiv Gandhi University of Knowledge Technologies"});
        team.push({name:"Sharath Dharmaji", school:"BITS Pilani Goa Campus"});
        team.push({name:"Shashank Arun-Gokhale", school:"BITS Pilani Goa Campus"});
        team.push({name:"Shashank Joshi", school:"Maharaja Agrasen Institute of Technology"});
        team.push({name:"Sonali Parashar", school:"Jaypee Institute Of Information Technology, Noida"});
        team.push({name:"Sukanya Venkataraman", school:"BITS Pilani Hyderabad Campus"});
        team.push({name:"Suprajha Shibiraj", school:"BMS College of Engineering"});
        team.push({name:"Tarun Khajuria", school:"Cluster Innovation Centre"});
        team.push({name:"Venkat Nirmal-Gavarraju", school:"BITS Pilani Hyderabad Campus"});
        team.push({name:"Venkata Neehar-Kurukunda", school:"Indian Institute of Technology, Guwahati"});
        team.push({name:"Vibhor Sehgal", school:"Maharaja Agrasen Institute of Technology"});
        team.push({name:"Vidit Mathur", school:"Jaypee Institute Of Information Technology, Noida"});
        team.push({name:"Yogitha Chilukuri", school:"BMS College of Engineering"});
        return team;
    }
});
