const cacheName = "list-v1";
const staticAssets = [
   "./screens/einstellungen/einstellungen.css",
   "./screens/einstellungen/einstellungen.js", 
   "./screens/einstellungen/einstellungen_mobile.css", 
   "./screens/einstellungen/index.php", 

   "./screens/freunde/freunde.css",
   "./screens/freunde/freunde.js", 
   "./screens/freunde/freunde_mobile.css", 
   "./screens/freunde/index.php",

   "./screens/produkte/produkte.css",
   "./screens/produkte/produkte.js", 
   "./screens/produkte/produkte_mobile.css", 
   "./screens/produkte/index.php",

   "./screens/index/index.css",
   "./screens/index/index.js", 
   "./screens/index/index_mobile.css", 
   "./screens/index/index.php",

   "./screens/zubereitung/zubereitung.css",
   "./screens/zubereitung/zubereitung.js", 
   "./screens/zubereitung/zubereitung_mobile.css", 
   "./screens/zubereitung/index.php",


]
self.addEventListener("install", async (e) => {
    const cache = await caches.open(cachName);
    await cache.addAll(staticAssets);
    return self.skipWaiting();
})

self.addEventListener("activate", (e) => {
    self.clients.claim();
})

self.addEventListener("fetch", async (e) => {
    const req = e.request;
    const url = new URL(req.url);

    if (url.origin === location.origin) {
        e.respondWith(cacheFirst(req));
    }
    else{
        e.respondWith(networkAndCache(req))
    }
})


async function cacheFirst(req) {
    const cache = await caches.open(cacheName);
    const cached = await cache.match(req);
    return cached || fetch(req)
}

async function networkAndCache(req) {
    const cache = await caches.open(cacheName);
    try {
        const fresh = await fetch(req)
        await cache.put(req, fresh.clone())
        return fresh
    } catch (error) {
        const cached = await cache.match(req)
        return cached
    }
}


