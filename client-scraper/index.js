const express = require('express')
const cors = require('cors')
const path = require('path')
const app = express();
const puppeteer = require('puppeteer');

app.use('/static', express.static(path.join(__dirname, 'public')));
app.use(express.json());
app.use(cors())

app.post('/search?', async (req, res) => {
 const result = await searchRecipes(req.query.s)
 res.json({
  success: true,
  result: result
 })
});

const port = process.env.PORT || 8082

app.listen(port)
console.log(`app is listening on port: ${ port }`)

async function searchRecipes(query) {
 // console.log(query)
 if (query == null) return 'No query'

 const browser = await puppeteer.launch();
 const page = await browser.newPage();

 await page.goto(`https://panlasangpinoy.com/?s=${ query }`)

 const cardRecipes = await page.$$(`article.post.tag-${ query }.category-recipes`);

 const searchResult = []
 for (const recipe of cardRecipes) {

  const recipeLink = await page.evaluate(el =>
   el.querySelector('header > h2 > a').getAttribute("href"),
   recipe
  )

  const recipeImage = await page.evaluate(el =>
   el.querySelector('div > a > img').getAttribute("src"),
   recipe
  )

  const recipeTitle = await page.evaluate(el =>
   el.querySelector('header > h2 > a').textContent,
   recipe
  )

  const recipeDescription = await page.evaluate(el =>
   el.querySelector('div > p:nth-child(2)').textContent,
   recipe
  )

  searchResult.push({ title: recipeTitle, image: recipeImage, link: recipeLink, description: recipeDescription })
 }

 // console.log(searchResult)
 if (searchResult.length == 0) {
  return 'No results found'
 }
 else {
  return searchResult
 }
}


async function getRecipe(query) {
 // console.log(query)
 if (query == null) return 'No query'

 const browser = await puppeteer.launch();
 const page = await browser.newPage();

 await page.goto(`${ query }`)

 const cardRecipes = await page.$$(`article.post.tag-${ query }.category-recipes`);

}


