function countWords(s) {
    const stopwords = {"a":1,"all":1,"an":1,"and":1,"are":1,"as":1,"at":1,"be":1,"but":1,
        "by":1,"can":1,"do":1,"for":1,"from":1,"had":1,"have":1,"if":1,"in":1,"is":1,
        "it":1,"me":1,"my":1,"no":1,"not":1,"of":1,"on":1,"or":1,"our":1,"s":1,"so":1,
        "t":1,"that":1,"the":1,"their":1,"they":1,"this":1,"to":1,"us":1,"was":1,"we":1,
        "who":1,"with":1,"you":1
    };

    /* Need to split up words, but NOT split on apostrophes. Solution is to
       first replace punctuation we don't care about (like fullstops and
       commas) with spaces, then extract all non-whitespace sequences. */
    s = s.toLowerCase();
    s = s.replace(/[\.\,\;\:\!\?\(\)\&]/g, ' ');
    let re = /\S+/ig;
    let m, word;
    let counts = {};
    while ((m = re.exec(s)) != null) {
        word = m[0];
        if (!stopwords[word]) {
            counts[word] = (counts[word] || 0) + 1;
        }
    }
    let results = [];
    for (let word in counts) {
        results.push([word, counts[word]]);
    }
    results = results.sort(function (a, b) {
        return ((a[1] < b[1]) ? -1 : ((a[1] > b[1]) ? 1 : 0));
    });
    results.reverse();
    return results;
}

function wordCountSum(words)
{
    let sum = 0;
    for (let i = 0; i < words.length; i++) {
        sum += words[i][1];
    }
    return sum;
}

function slugify(string) {
    const a = 'àáäâãåăæąçćčđďèéěėëêęğǵḧìíïîįłḿǹńňñòóöôœøṕŕřßşśšșťțùúüûǘůűūųẃẍÿýźžż·/_,:;'
    const b = 'aaaaaaaaacccddeeeeeeegghiiiiilmnnnnooooooprrsssssttuuuuuuuuuwxyyzzz------'
    const p = new RegExp(a.split('').join('|'), 'g')

    return string.toString().toLowerCase()
        .replace(/\s+/g, '-') // Replace spaces with -
        .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
        .replace(/&/g, '-and-') // Replace & with 'and'
        .replace(/[^\w\-]+/g, '') // Remove all non-word characters
        .replace(/\-\-+/g, '-') // Replace multiple - with single -
        .replace(/^-+/, '') // Trim - from start of text
        .replace(/-+$/, '') // Trim - from end of text
}
