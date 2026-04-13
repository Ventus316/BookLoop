import React, { useState, useEffect, useRef, useMemo } from 'react';
import * as PIXI from 'pixi.js';
import { Search, Heart, BookOpen, ChevronRight, Share2, Filter, X } from 'lucide-react';

// --- Mock Data 模擬資料庫 ---
const MOCK_BOOKS = [
  { id: 1, title: '演算法導論 (第四版)', author: 'Thomas H. Cormen', category: '資工', donor: '陳同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=300&h=400', description: '近全新，僅翻閱過幾次，無劃記。適合準備研究所考試。' },
  { id: 2, title: '設計的心理學', author: 'Don Norman', category: '設計', donor: '林同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=300&h=400', description: '封面有輕微折痕，內頁乾淨。UX 設計師必讀經典。' },
  { id: 3, title: 'Clean Code 無瑕的程式碼', author: 'Robert C. Martin', category: '資工', donor: '張同學', status: 'reserved', coverUrl: 'https://images.unsplash.com/photo-1555662800-87311b3a51d9?auto=format&fit=crop&q=80&w=300&h=400', description: '有螢光筆劃記重點，介意者勿領。' },
  { id: 4, title: '百年孤寂', author: 'Gabriel García Márquez', category: '文學', donor: '王同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=300&h=400', description: '經典魔幻寫實小說，書況良好。' },
  { id: 5, title: '微積分 (下)', author: 'James Stewart', category: '大一必修', donor: '李同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&q=80&w=300&h=400', description: '期末考救星，有筆記。' },
  { id: 6, title: '社會學與台灣社會', author: '王振寰', category: '通識', donor: '吳同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=300&h=400', description: '通識課用書，九成新。' },
  { id: 7, title: '資料結構 (C++版)', author: 'Ellis Horowitz', category: '資工', donor: '劉同學', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&q=80&w=300&h=400', description: '有附光碟，書況中等。' },
  { id: 8, title: '原子習慣', author: 'James Clear', category: '通識', donor: '趙同學', status: 'reserved', coverUrl: 'https://images.unsplash.com/photo-1589998059171-989d887dda19?auto=format&fit=crop&q=80&w=300&h=400', description: '培養好習慣的實用指南，已被人預約。' },
];

const CATEGORIES = ['資工', '設計', '文學', '通識', '大一必修'];

// --- 共用組件：導覽列 Navbar ---
const Navbar = ({ currentView, setCurrentView }) => {
  return (
    <nav className="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex justify-between items-center">
      <div 
        className="flex items-center gap-2 text-emerald-700 font-bold text-2xl tracking-wide cursor-pointer hover:opacity-80 transition-opacity"
        onClick={() => setCurrentView('home')}
      >
        <BookOpen className="w-8 h-8" />
        <span>書活</span>
      </div>
      
      <div className="hidden md:flex gap-6 text-slate-600 font-medium">
        <button 
          onClick={() => setCurrentView('search')} 
          className={`transition-colors ${currentView === 'search' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'}`}
        >
          尋書大廳
        </button>
        <button className="hover:text-emerald-600 transition-colors">如何捐贈</button>
        <button className="hover:text-emerald-600 transition-colors">流向足跡</button>
      </div>

      <div className="flex gap-3">
        <button className="hidden sm:block text-emerald-700 font-medium px-4 py-2 hover:bg-emerald-50 rounded-lg transition-colors">登入</button>
        <button className="bg-emerald-600 text-white font-medium px-5 py-2 rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all">
          我要捐書
        </button>
      </div>
    </nav>
  );
};

// --- 共用組件：PixiJS 動態插畫 (相容 PixiJS v8) ---
const PixiIllustration = () => {
  const pixiContainer = useRef(null);

  useEffect(() => {
    if (!pixiContainer.current) return;

    const app = new PIXI.Application();
    let isMounted = true;

    const initPixi = async () => {
      await app.init({
        width: 500,
        height: 500,
        backgroundAlpha: 0, 
        resolution: window.devicePixelRatio || 1,
        antialias: true,
      });

      if (!isMounted) {
        app.destroy(true, { children: true });
        return;
      }

      pixiContainer.current.appendChild(app.canvas);

      const books = [];
      const colors = [0x059669, 0xfbbf24, 0x34d399, 0xcbd5e1]; 

      for (let i = 0; i < 4; i++) {
        const bookGroup = new PIXI.Container();

        const bookBody = new PIXI.Graphics();
        bookBody.rect(0, 0, 70, 100).fill(colors[i]);

        const pages = new PIXI.Graphics();
        pages.rect(8, 5, 20, 90).fill({ color: 0xffffff, alpha: 0.8 });

        bookGroup.addChild(bookBody);
        bookGroup.addChild(pages);
        bookGroup.pivot.set(35, 50);
        
        app.stage.addChild(bookGroup);
        
        books.push({
          sprite: bookGroup,
          angle: (i * Math.PI) / 2,
          orbitSpeed: 0.015,
          rotationSpeed: 0.02
        });
      }

      app.ticker.add(() => {
        books.forEach((b) => {
          b.angle += b.orbitSpeed;
          b.sprite.x = 250 + Math.cos(b.angle) * 150;
          b.sprite.y = 250 + Math.sin(b.angle) * 150;
          b.sprite.rotation += b.rotationSpeed;
        });
      });
    };

    initPixi();

    return () => {
      isMounted = false;
      try {
        app.destroy(true, { children: true, texture: true, baseTexture: true });
      } catch (e) {
        console.log("Pixi cleanup:", e);
      }
    };
  }, []);

  return (
    <div className="relative w-full flex justify-center items-center drop-shadow-xl">
      <div className="absolute w-72 h-72 bg-emerald-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-pulse"></div>
      <div className="absolute w-72 h-72 bg-amber-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 right-10 top-10 animate-pulse delay-700"></div>
      <div ref={pixiContainer} className="relative z-10 w-[500px] h-[500px]"></div>
    </div>
  );
};

// --- 視圖 1：首頁 (HomeView) ---
const HomeView = ({ setCurrentView }) => {
  const [stats, setStats] = useState({ donated: 0, claimed: 0 });

  useEffect(() => {
    let currentDonated = 0;
    let currentClaimed = 0;
    const targetDonated = 1254;
    const targetClaimed = 982;
    
    const interval = setInterval(() => {
      currentDonated += Math.ceil((targetDonated - currentDonated) / 10);
      currentClaimed += Math.ceil((targetClaimed - currentClaimed) / 10);
      
      setStats({ donated: currentDonated, claimed: currentClaimed });
      
      if (currentDonated >= targetDonated && currentClaimed >= targetClaimed) {
        clearInterval(interval);
      }
    }, 50);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="animate-in fade-in duration-500">
      {/* 英雄區 */}
      <main className="max-w-7xl mx-auto px-6 pt-12 pb-20 lg:pt-24 lg:pb-32 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div className="space-y-8 z-10">
          <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-700 font-medium text-sm">
            <span className="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            讓資源延續，讓知識循環
          </div>
          <h1 className="text-5xl lg:text-7xl font-extrabold text-slate-900 leading-tight">
            讓知識在 <br/>
            <span className="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-emerald-700">校園流動</span>
          </h1>
          <p className="text-lg text-slate-600 max-w-lg leading-relaxed">
            打破書櫃的空間限制，將閒置的課本與讀物傳遞給下一位需要的同學。建立透明的捐贈歷程，打造綠色永續的校園閱讀社群。
          </p>
          <div className="flex flex-col sm:flex-row gap-4 pt-4">
            <button 
              onClick={() => setCurrentView('search')}
              className="flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl text-lg font-bold hover:bg-emerald-700 hover:-translate-y-1 shadow-xl shadow-emerald-200 transition-all duration-300"
            >
              <Search className="w-5 h-5" />
              立即尋書
            </button>
            <button className="flex items-center justify-center gap-2 bg-white text-emerald-700 border-2 border-emerald-600 px-8 py-4 rounded-xl text-lg font-bold hover:bg-emerald-50 hover:-translate-y-1 shadow-lg shadow-slate-100 transition-all duration-300">
              <Share2 className="w-5 h-5" />
              我要捐贈
            </button>
          </div>
        </div>
        <div className="relative flex justify-center lg:justify-end">
          <PixiIllustration />
        </div>
      </main>

      {/* 數據儀表板 */}
      <section className="max-w-5xl mx-auto px-6 relative -mt-12 mb-20 z-20">
        <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex flex-col md:flex-row justify-around items-center gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
          <div className="text-center w-full">
            <p className="text-slate-500 font-medium mb-2">目前已累計捐贈書籍</p>
            <div className="text-5xl font-black text-emerald-600 font-mono tracking-tight">
              {stats.donated.toLocaleString()} <span className="text-xl text-slate-400 font-sans">本</span>
            </div>
          </div>
          <div className="text-center w-full pt-8 md:pt-0">
            <p className="text-slate-500 font-medium mb-2">成功傳遞知識次數</p>
            <div className="text-5xl font-black text-amber-500 font-mono tracking-tight">
              {stats.claimed.toLocaleString()} <span className="text-xl text-slate-400 font-sans">次</span>
            </div>
          </div>
        </div>
      </section>

      {/* 最新上架書籍 */}
      <section className="max-w-7xl mx-auto px-6 pb-32">
        <div className="flex justify-between items-end mb-8">
          <div>
            <h2 className="text-3xl font-bold text-slate-900 mb-2">最新待領取書籍</h2>
            <p className="text-slate-500">搶先發現校園內剛上架的二手好書</p>
          </div>
          <button 
            onClick={() => setCurrentView('search')}
            className="hidden sm:flex items-center gap-1 text-emerald-600 font-semibold hover:text-emerald-800 transition-colors"
          >
            查看全部 <ChevronRight className="w-5 h-5" />
          </button>
        </div>

        <div className="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory" style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}>
          {MOCK_BOOKS.map((book) => (
            <div key={book.id} className="min-w-[240px] max-w-[240px] flex-shrink-0 bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-emerald-100 hover:-translate-y-2 transition-all duration-300 snap-start group cursor-pointer">
              <div className="relative w-full h-64 mb-4 rounded-xl overflow-hidden bg-slate-100">
                <img src={book.coverUrl} alt={book.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                {book.status === 'available' && (
                  <div className="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                    <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span className="text-xs font-bold text-emerald-700">待領取</span>
                  </div>
                )}
                <button className="absolute top-3 right-3 p-2 bg-white/90 rounded-full text-slate-400 hover:text-amber-500 hover:bg-white shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-200">
                  <Heart className="w-4 h-4" />
                </button>
              </div>
              <div className="space-y-2">
                <h3 className="font-bold text-slate-900 text-lg line-clamp-1 group-hover:text-emerald-600 transition-colors">{book.title}</h3>
                <div className="flex items-center justify-between mt-3 pt-3 border-t border-slate-50">
                  <span className="text-xs font-medium text-slate-400 bg-slate-100 px-2 py-1 rounded-md">捐贈者</span>
                  <span className="text-sm font-semibold text-slate-600">{book.donor}</span>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
};

// --- 視圖 2：書籍搜尋大廳 (SearchView) ---
const SearchView = () => {
  const [searchTerm, setSearchTerm] = useState('');
  const [isSearchFocused, setIsSearchFocused] = useState(false);
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [showOnlyAvailable, setShowOnlyAvailable] = useState(false);
  const [isMobileFilterOpen, setIsMobileFilterOpen] = useState(false);

  const searchSuggestions = useMemo(() => {
    if (!searchTerm.trim()) return [];
    return MOCK_BOOKS
      .filter(book => book.title.toLowerCase().includes(searchTerm.toLowerCase()))
      .map(book => book.title)
      .slice(0, 5);
  }, [searchTerm]);

  const filteredBooks = useMemo(() => {
    return MOCK_BOOKS.filter(book => {
      const matchSearch = book.title.toLowerCase().includes(searchTerm.toLowerCase()) || 
                          book.author.toLowerCase().includes(searchTerm.toLowerCase());
      const matchCategory = selectedCategories.length === 0 || selectedCategories.includes(book.category);
      const matchStatus = showOnlyAvailable ? book.status === 'available' : true;
      return matchSearch && matchCategory && matchStatus;
    });
  }, [searchTerm, selectedCategories, showOnlyAvailable]);

  const toggleCategory = (category) => {
    setSelectedCategories(prev => 
      prev.includes(category) ? prev.filter(c => c !== category) : [...prev, category]
    );
  };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col md:flex-row gap-8 animate-in fade-in duration-500">
      
      {/* 行動版過濾按鈕 */}
      <div className="md:hidden flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold text-slate-900">尋書大廳</h1>
        <button 
          onClick={() => setIsMobileFilterOpen(true)}
          className="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-slate-600 font-medium"
        >
          <Filter className="w-4 h-4" /> 篩選
        </button>
      </div>

      {/* 左側邊欄 */}
      <aside className={`fixed inset-0 z-50 bg-white md:bg-transparent md:static md:block md:w-64 flex-shrink-0 transition-transform duration-300 ease-in-out ${isMobileFilterOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}`}>
        <div className="h-full overflow-y-auto p-6 md:p-0 md:sticky md:top-24">
          <div className="flex justify-between items-center md:hidden mb-6">
            <h2 className="text-xl font-bold text-slate-900">篩選條件</h2>
            <button onClick={() => setIsMobileFilterOpen(false)} className="p-2 text-slate-400 hover:text-slate-600">
              <X className="w-6 h-6" />
            </button>
          </div>

          <div className="mb-8">
            <h3 className="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">書籍狀態</h3>
            <label className="flex items-center gap-3 cursor-pointer group">
              <div className={`w-5 h-5 rounded border flex items-center justify-center transition-colors ${showOnlyAvailable ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-slate-300 group-hover:border-emerald-400'}`}>
                {showOnlyAvailable && <div className="w-2.5 h-2.5 bg-white rounded-sm" />}
              </div>
              <span className="text-slate-700 font-medium group-hover:text-emerald-700 transition-colors">僅顯示「待領取」</span>
            </label>
          </div>

          <div>
            <h3 className="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">書籍類別</h3>
            <div className="space-y-3">
              {CATEGORIES.map(category => (
                <label key={category} className="flex items-center gap-3 cursor-pointer group">
                  <input type="checkbox" className="hidden" checked={selectedCategories.includes(category)} onChange={() => toggleCategory(category)} />
                  <div className={`w-5 h-5 rounded border flex items-center justify-center transition-colors ${selectedCategories.includes(category) ? 'bg-emerald-500 border-emerald-500' : 'bg-white border-slate-300 group-hover:border-emerald-400'}`}>
                    {selectedCategories.includes(category) && <div className="w-2.5 h-2.5 bg-white rounded-sm" />}
                  </div>
                  <span className="text-slate-700 font-medium group-hover:text-emerald-700 transition-colors">{category}</span>
                </label>
              ))}
            </div>
          </div>
          
          <div className="md:hidden mt-8">
            <button onClick={() => setIsMobileFilterOpen(false)} className="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg">套用篩選</button>
          </div>
        </div>
      </aside>

      {/* 主內容區 */}
      <div className="flex-1">
        <h1 className="hidden md:block text-3xl font-bold text-slate-900 mb-6">尋書大廳</h1>
        
        <div className="relative mb-8 z-40">
          <div className={`relative flex items-center bg-white rounded-2xl border-2 transition-all duration-300 shadow-sm ${isSearchFocused ? 'border-emerald-500 shadow-emerald-100 ring-4 ring-emerald-50' : 'border-slate-200 hover:border-slate-300'}`}>
            <Search className={`w-6 h-6 ml-4 ${isSearchFocused ? 'text-emerald-500' : 'text-slate-400'}`} />
            <input 
              type="text" 
              placeholder="搜尋書名、作者..." 
              className="w-full py-4 px-4 bg-transparent outline-none text-slate-700 text-lg placeholder:text-slate-400 font-medium"
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              onFocus={() => setIsSearchFocused(true)}
              onBlur={() => setTimeout(() => setIsSearchFocused(false), 200)}
            />
            {searchTerm && (
              <button onClick={() => setSearchTerm('')} className="p-2 mr-2 text-slate-400 hover:text-slate-600">
                <X className="w-5 h-5" />
              </button>
            )}
          </div>

          {isSearchFocused && searchSuggestions.length > 0 && (
            <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden divide-y divide-slate-50">
              {searchSuggestions.map((suggestion, index) => (
                <div 
                  key={index}
                  className="px-6 py-3 hover:bg-emerald-50 text-slate-700 font-medium cursor-pointer transition-colors"
                  onClick={() => setSearchTerm(suggestion)}
                >
                  <Search className="inline w-4 h-4 mr-3 text-slate-400" />
                  {suggestion}
                </div>
              ))}
            </div>
          )}
        </div>

        <div className="mb-6 flex justify-between items-center">
          <p className="text-slate-500 font-medium">找到 <span className="text-emerald-600 font-bold text-lg">{filteredBooks.length}</span> 本書籍</p>
        </div>

        {filteredBooks.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {filteredBooks.map((book) => (
              <div key={book.id} className="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-emerald-900/10 hover:-translate-y-2 transition-all duration-300 cursor-pointer flex flex-col h-full">
                <div className="relative w-full h-64 bg-slate-100 overflow-hidden flex-shrink-0">
                  <img src={book.coverUrl} alt={book.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                  
                  <div className="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                    <span className={`w-2.5 h-2.5 rounded-full ${book.status === 'available' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'}`}></span>
                    <span className={`text-xs font-bold ${book.status === 'available' ? 'text-emerald-700' : 'text-amber-700'}`}>
                      {book.status === 'available' ? '待領取' : '已預約'}
                    </span>
                  </div>

                  <button className="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur-md rounded-full text-slate-400 hover:text-amber-500 hover:bg-white shadow-sm opacity-0 group-hover:opacity-100 transition-all duration-200 z-10">
                    <Heart className="w-4 h-4" />
                  </button>

                  <div className="absolute inset-0 bg-slate-900/80 backdrop-blur-sm p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end">
                    <p className="text-white text-sm leading-relaxed line-clamp-4 font-medium mb-2">{book.description}</p>
                    <div className="flex items-center gap-2 text-emerald-300 text-sm">
                      <BookOpen className="w-4 h-4" /><span>點擊查看詳情</span>
                    </div>
                  </div>
                </div>

                <div className="p-5 flex flex-col flex-grow bg-white relative z-10">
                  <div className="mb-1">
                    <span className="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">{book.category}</span>
                  </div>
                  <h3 className="font-bold text-slate-900 text-lg leading-tight mb-1 line-clamp-2" title={book.title}>{book.title}</h3>
                  <p className="text-sm text-slate-500 mb-4 line-clamp-1">{book.author}</p>
                  
                  <div className="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div className="flex items-center gap-2">
                      <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                        {book.donor.charAt(0)}
                      </div>
                      <span className="text-sm font-medium text-slate-600">{book.donor}</span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="w-full py-20 flex flex-col items-center justify-center text-center bg-white rounded-3xl border border-dashed border-slate-300">
            <div className="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6">
              <Search className="w-10 h-10 text-slate-400" />
            </div>
            <h3 className="text-xl font-bold text-slate-800 mb-2">找不到符合的書籍</h3>
            <p className="text-slate-500 max-w-md">試試看更換搜尋關鍵字，或是放寬左側的篩選條件。也許你想找的書即將上架！</p>
            <button 
              onClick={() => { setSearchTerm(''); setSelectedCategories([]); setShowOnlyAvailable(false); }}
              className="mt-6 px-6 py-2 bg-emerald-50 text-emerald-700 font-bold rounded-lg hover:bg-emerald-100 transition-colors"
            >
              清除所有條件
            </button>
          </div>
        )}
      </div>
    </div>
  );
};

// --- 主應用程式入口 ---
export default function App() {
  const [currentView, setCurrentView] = useState('home');

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-800 selection:bg-emerald-200 selection:text-emerald-900 overflow-x-hidden">
      <Navbar currentView={currentView} setCurrentView={setCurrentView} />
      
      {/* 依據狀態渲染不同頁面 */}
      {currentView === 'home' ? (
        <HomeView setCurrentView={setCurrentView} />
      ) : (
        <SearchView />
      )}

      {/* 隱藏橫向捲動條的 CSS */}
      <style dangerouslySetInnerHTML={{__html: `
        .hide-scrollbar::-webkit-scrollbar { display: none; }
      `}} />
    </div>
  );
}