@extends('includes.header')
@section('body')

<div class="pt-2 container">
  <button class="btn btn-secondary dropdown-toggle" type="button" id="filter" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
    Card Filter
  </button>
  <ul class="dropdown-menu dropdown-menu-dark p-2 " aria-labelledby="filter">
    <p>Mana Type</p>
    <li>
        <a>
            <input onclick="addColor('White')" type="checkbox" class="btn-check" id="whiteMana" autocomplete="off">
            <label class="btn btn-outline-light" for="whiteMana">White</label>
        </a>

        <a>        
            <input onclick="addColor('Blue')" type="checkbox" class="btn-check" id="blueMana" autocomplete="off">
            <label class="btn btn-outline-primary" for="blueMana">Blue</label>
        </a>

        <a>
            <input onclick="addColor('Black')" type="checkbox" class="btn-check" id="blackMana" autocomplete="off">
            <label class="btn btn-outline-secondary" for="blackMana">Black</label>
        </a>

        <a>
            <input onclick="addColor('Red')" type="checkbox" class="btn-check" id="redMana" autocomplete="off">
            <label class="btn btn-outline-danger" for="redMana">Red</label>
        </a>        

        <a>
            <input onclick="addColor('Green')" type="checkbox" class="btn-check" id="greenMana" autocomplete="off">
            <label class="btn btn-outline-success" for="greenMana">Green</label>
        </a>
    </li>
    <li><hr class="dropdown-divider"></li>

    <!-- Type Filter -->
    <p>Type</p>
    <li>
        <a>
            <input onclick="addType('Artifact')" type="radio" class="btn-check" name="typeFilter" id="artifact" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="artifact">Artifact</label>
        </a>
        <a>
            <input onclick="addType('Creature')" type="radio" class="btn-check" name="typeFilter" id="creature" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="creature">Creature</label>
        </a>
        <a>
            <input onclick="addType('Enchantment')" type="radio" class="btn-check" name="typeFilter" id="enchantment" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="enchantment">Enchantment</label>
        </a>
        <a>
            <input onclick="addType('Instant')" type="radio" class="btn-check" name="typeFilter" id="instant" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="instant">Instant</label>
        </a>
        <a>
            <input onclick="addType('Land')" type="radio" class="btn-check" name="typeFilter" id="land" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="land">Land</label>
        </a>
        <a>
            <input onclick="addType('Planeswalker')" type="radio" class="btn-check" name="typeFilter" id="planeswalker" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="planeswalker">Planeswalker</label>
        </a>
        <a>
            <input onclick="addType('Sorcery')" type="radio" class="btn-check" name="typeFilter" id="sorcery" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="sorcery">Sorcery</label>
        </a>
    </li>
    <li><hr class="dropdown-divider"></li>

    <!-- Rarity Filter -->
    <li>
    <p>Rarity</p>
        <a>
            <input onclick="addRarity('Common')" type="radio" class="btn-check" name="rarityFilter" id="common" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="common">common</label>
        </a>
        <a>
            <input onclick="addRarity('Uncommon')" type="radio" class="btn-check" name="rarityFilter" id="uncommon" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="uncommon">Uncommon</label>
        </a>
        <a>
            <input onclick="addRarity('Rare')" type="radio" class="btn-check" name="rarityFilter" id="rare" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="rare">Rare</label>
        </a>
        <a>
            <input onclick="addRarity('Mythic')" type="radio" class="btn-check" name="rarityFilter" id="mythicRare" autocomplete="off">
            <label class="btn btn-outline-warning mb-1" for="mythicRare">Mythic Rare</label> 
        </a>         
    </li>
    <li><hr class="dropdown-divider"></li>

    <!-- Search by Name -->
    <p>Search by Name</p>
    <li>
        <form class="row">
            <div class="col-8" style="margin-right: -1em;">
                <input type="text" class="form-control" id="searchByName" placeholder="Search">
            </div>
            <div class="col-2">
                <button type="submit" class="btn btn-primary">Go</button>
            </div>
        </form>
    </li>

  </ul>
</div>

<div class="container mb-5 py-5 d-flex justify-content-center flex-wrap" id="cardResults">

    
</div>

<div class="container spinner-border text-warning d-flex justify-content-center" style="width: 3rem; height: 3rem;" role="status">
  <span class="sr-only">Loading...</span>
</div>

<div class="d-flex justify-content-center fixed-bottom p-2" style="background-color: rgb(78, 78, 78);" id="pageButtons">
    <button class="btn btn-primary" onclick="displayResults('NA', 'prev')"><i class="fa fa-circle-chevron-left"></i></button>
    <li class="page-item disabled bg-primary border-0 rounded mx-2">
      <a id="currentpage" class="page-link bg-primary border-0 rounded text-light" href="#" tabindex="-1">1</a>
    </li>
    <button class="btn btn-primary" onclick="displayResults('NA', 'next')"><i class="fa fa-circle-chevron-right"></i></button>
</div>

<!-- Filter by Color -->
<script>
    let ALLCARDS;
    let CURRENTCARDS;
    let currentPage;
    let COLORS = [];
    let TYPE;
    let RARITY;

    $(document).ready(function() {
        axios.get('{{ route('getAllCards') }}')
        .then(function(response) {
            ALLCARDS = response.data;
            displayResults(ALLCARDS, "first");
        });
    });

    function addColor(color) {
        if(COLORS.includes(color)) {
            COLORS.splice(COLORS.indexOf(color), 1);
            console.log(color + " unclicked");
        }
        else {
            COLORS.push(color);
            console.log(color + " clicked");
        }
        displayResults(ALLCARDS, "first");
    }

    function colorFilterJSON() {
        let colorString = "?color=" + COLORS.join(',');

        axios.get('{{ route('getCardsByColor') }}' + colorString)
        .then(function(response) {
            return response.data;
        });
    }

    function addType(type) {
        if(TYPE == type)
            TYPE = NULL;
        else
            TYPE = type;
        displayResults(ALLCARDS, "first");
    }
    
    function typeFilterJSON() {
        typeString = "?type=" + TYPE;
        axios.get('{{ route('getCardsByType') }}' + typeString )
        .then(function(response) {
            return response.data;
        });
    }

    function addRarity(rare) {
        if(RARITY == rare)
            RARITY = NULL;
        else
            RARITY = rare;
        displayResults(ALLCARDS, "first");
    }

    function multiFilter() {
        let apiCall = "?";
        if(COLORS.length) {
            apiCall += "color=" + COLORS.join(',') + "&";
        }
        if(RARITY) {
            apiCall += "rarity=" + RARITY + "&";
        }
        if(TYPE) {
            apiCall += "type=" + TYPE + "&";
        }

        console.log(apiCall);
        axios.get('{{ route('getMultiFilter') }}' + apiCall)
        .then(function(response) {
            return response.data;
        });
    }

    function displayResults(cards, direction) {
        //check if we have unchecked all of the other filters,
        //if this is the case then we need to load the default set back in
        //update the currentCards that we are looking at
        //this will be used to control our pagination
        let pageNum = 0;
        if(cards == "NA")
            cards = CURRENTCARDS;
        if(!COLORS.length && !TYPE && !RARITY)
            cards = ALLCARDS;
        else
            cards = multiFilter();
            
        CURRENTCARDS = cards;

        let totalPages = cards.length / 50;
        if(direction == "first") {
            pageNum = 0;
        } 
        if(direction == "next" && currentPage < totalPages) {
            pageNum = currentPage + 1;
        }
        if(direction == "prev" && currentPage > 0) {
            pageNum = currentPage - 1;
        }
        currentPage = pageNum;
        $("#currentpage").html(currentPage+1);

        //display the first 50 cards and then make buttons dynamically for the pages
        //if pageNum is specified then display that specific page of info
        let firstIndex = pageNum * 50;
        let lastIndex = (pageNum + 1) * 50;


        //unload any previous cards that exist on the page
        $(".mtgCard").remove();

        //loop through the cards and display them on the page
        for(let i = firstIndex; i < lastIndex; i++) {
            let image = $("<img/>", {
                    src: cards[i].image_url,
                    class: "align-middle m-2 mtgCard",
                    alt: "MTG Card", 
                    height: "300px"
            });
            image.appendTo($('#cardResults'));
        }
    }

    function filterByColor(color) {
        if(COLORS.includes(color)) {
            COLORS.splice(COLORS.indexOf(color), 1);
            console.log(color + " unclicked");
        }
        else {
            COLORS.push(color);
            console.log(color + " clicked");
        }
        console.log(COLORS);

        let colorString = "?color=" + COLORS.join(',');

        axios.get('{{ route('getCardsByColor') }}' + colorString)
        .then(function(response) {
            console.log(response.data);
            return response.data;
            //displayResults(response.data, 0);
        });
    }

    function filterByType(type) {
        typeString = "?type=" + type;
        axios.get('{{ route('getCardsByType') }}' + typeString )
        .then(function(response) {
            displayResults(response.data, 0);
        });
    }

    function filterByRarity(rarity) {
        rarityString = "?rarity=" + rarity;
        axios.get('{{ route('getCardsByRarity') }}' + rarityString)
        .then(function(response) {
            displayResults(response.data, 0);
        });
    }

</script>

@endsection




<!--$allCards[0]->card_name
tags:

MULTI-BUTTON:
colors

TWO SEARCH BOX RANGE:
manacost

DROPDOWNS:
type
rarity

SEARCH-BY-DROPDOWN:
id
card_name
multiverseid
card_set

OTHER:
image_url
created_at
updated_at
-->