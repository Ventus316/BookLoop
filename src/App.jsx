import React, { useState, useEffect, useRef, useMemo } from 'react';
import * as PIXI from 'pixi.js';
import { 
  Search, Heart, BookOpen, ChevronRight, Share2, Filter, X, 
  ArrowLeft, ThumbsUp, MessageSquare, Mail, Calendar, Hash, User, Building2, Clock, CheckCircle2, AlertCircle,
  LogOut, Edit, Trash2, PauseCircle, Send, UploadCloud, Loader2, BookPlus, Settings, HelpCircle
} from 'lucide-react';

// --- Mock Data 模擬資料庫 ---
const MOCK_BOOKS = [
  { id: 1, title: '演算法導論 (第四版)', author: 'Thomas H. Cormen', category: '資工', donor: '陳同學', donorContact: 's110xxxx45@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=600&h=800', description: '近全新，僅翻閱過幾次，無劃記。適合準備研究所考試。希望這本書能幫助到有心鑽研演算法的學弟妹。', isbn: '9789862803144', publisher: '碁峰資訊', publishDate: '2023/09', likes: 12, saves: 5, history: [{ id: 1, date: '2026/04/10 14:30', action: '陳同學 上傳了此書籍' }, { id: 2, date: '2026/04/11 09:15', action: '系統審核通過，狀態更新為「待領取」' }], comments: [{ id: 1, user: '大一學弟', date: '2026/04/12', text: '請問這本有缺頁嗎？' }, { id: 2, user: '陳同學', isDonor: true, date: '2026/04/13', text: '沒有缺頁喔！保存得很完整。' }] },
  { id: 2, title: '設計的心理學', author: 'Don Norman', category: '設計', donor: '林同學', donorContact: 's111xxxx23@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=600&h=800', description: '封面有輕微折痕，內頁乾淨。UX 設計師必讀經典。', isbn: '9789573274710', publisher: '遠流', publishDate: '2014/11', likes: 8, saves: 24, history: [{ id: 1, date: '2026/04/05', action: '林同學 上傳了此書籍' }], comments: [] },
  { id: 3, title: 'Clean Code 無瑕的程式碼', author: 'Robert C. Martin', category: '資工', donor: '張同學', donorContact: 's109xxxx88@yzu.edu.tw', status: 'reserved', coverUrl: 'https://images.unsplash.com/photo-1555662800-87311b3a51d9?auto=format&fit=crop&q=80&w=600&h=800', description: '有螢光筆劃記重點，介意者勿領。這本書陪伴我度過軟體工程的專題，希望能傳承下去。', isbn: '9789862017050', publisher: '博碩文化', publishDate: '2013/04', likes: 45, saves: 12, history: [{ id: 1, date: '2026/03/20', action: '張同學 上傳了此書籍' }, { id: 2, date: '2026/04/12', action: '李學弟 提出了領取請求，狀態更新為「已預約」' }], comments: [] },
  { id: 4, title: '百年孤寂', author: 'Gabriel García Márquez', category: '文學', donor: '王同學', donorContact: 'wang@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&q=80&w=600&h=800', description: '經典魔幻寫實小說，書況良好。', isbn: '9789863445343', publisher: '皇冠', publishDate: '2018/02', likes: 5, saves: 2, history: [{id: 1, date: '2026/04/13', action: '王同學 上傳了此書籍'}], comments: [] },
  { id: 5, title: '微積分 (下)', author: 'James Stewart', category: '大一必修', donor: '李同學', donorContact: 'lee@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1509869175650-a1d97972541a?auto=format&fit=crop&q=80&w=600&h=800', description: '期末考救星，有筆記。', isbn: '9789862803151', publisher: '滄海', publishDate: '2022/08', likes: 15, saves: 30, history: [{id: 1, date: '2026/04/13', action: '李同學 上傳'}], comments: [] },
  { id: 6, title: '社會學與台灣社會', author: '王振寰', category: '通識', donor: '吳同學', donorContact: 'wu@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1561070791-2526d30994b5?auto=format&fit=crop&q=80&w=600&h=800', description: '通識課用書，九成新。', isbn: '9789571181249', publisher: '巨流', publishDate: '2015/09', likes: 3, saves: 1, history: [{id: 1, date: '2026/04/13', action: '吳同學 上傳'}], comments: [] },
  { id: 7, title: '資料結構 (C++版)', author: 'Ellis Horowitz', category: '資工', donor: '劉同學', donorContact: 'liu@yzu.edu.tw', status: 'available', coverUrl: 'https://images.unsplash.com/photo-1515879218367-8466d910aaa4?auto=format&fit=crop&q=80&w=600&h=800', description: '有附光碟，書況中等。', isbn: '9789862800112', publisher: '文魁', publishDate: '2010/06', likes: 7, saves: 4, history: [{id: 1, date: '2026/04/13', action: '劉同學 上傳'}], comments: [] },
  { id: 8, title: '原子習慣', author: 'James Clear', category: '通識', donor: '趙同學', donorContact: 'chao@yzu.edu.tw', status: 'reserved', coverUrl: 'https://images.unsplash.com/photo-1589998059171-989d887dda19?auto=format&fit=crop&q=80&w=600&h=800', description: '培養好習慣的實用指南，已被人預約。', isbn: '9789861755267', publisher: '方智', publishDate: '2019/06', likes: 88, saves: 42, history: [{id: 1, date: '2026/04/13', action: '趙同學 上傳'}], comments: [] },
];

const CATEGORIES = ['資工', '設計', '文學', '通識', '大一必修'];

// --- 共用組件：導覽列 Navbar ---
const Navbar = ({ currentView, setCurrentView, user }) => {
  return (
    <nav className="sticky top-0 z-50 bg-white/80 backdrop-blur-md border-b border-slate-200 px-6 py-4 flex justify-between items-center">
      <div 
        className="flex items-center gap-2 text-emerald-700 font-bold text-2xl tracking-wide cursor-pointer hover:opacity-80 transition-opacity"
        onClick={() => setCurrentView('home')}
      >
        <BookOpen className="w-8 h-8" />
        <span>書活</span>
      </div>
      
      {/* 隱藏於登入頁面的中間導覽 */}
      {currentView !== 'auth' && (
        <div className="hidden md:flex gap-6 text-slate-600 font-medium">
          <button onClick={() => setCurrentView('search')} className={`transition-colors ${currentView === 'search' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'}`}>尋書大廳</button>
          <button onClick={() => setCurrentView('how-to')} className={`transition-colors ${currentView === 'how-to' ? 'text-emerald-600 font-bold' : 'hover:text-emerald-600'}`}>如何捐贈</button>
          <button className="hover:text-emerald-600 transition-colors">流向足跡</button>
        </div>
      )}

      <div className="flex gap-3 items-center">
        {currentView !== 'auth' && !user && (
          <button 
            onClick={() => setCurrentView('auth')}
            className="hidden sm:block text-emerald-700 font-medium px-4 py-2 hover:bg-emerald-50 rounded-lg transition-colors"
          >
            登入
          </button>
        )}
        
        {user && (
          <div 
            className="flex items-center gap-2 cursor-pointer p-1.5 pr-3 rounded-full bg-slate-100 hover:bg-emerald-50 transition-colors border border-slate-200 hover:border-emerald-200"
            onClick={() => setCurrentView('dashboard')}
            title="前往個人後台"
          >
            <div className="w-8 h-8 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-800 font-bold text-sm">
              {user.name.charAt(0)}
            </div>
            <span className="hidden sm:block font-bold text-slate-700 text-sm">{user.name}</span>
          </div>
        )}

        <button 
          onClick={() => setCurrentView(user ? 'donate' : 'auth')}
          className="bg-emerald-600 text-white font-medium px-5 py-2 rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all"
        >
          我要捐書
        </button>
      </div>
    </nav>
  );
};

// --- 共用組件：PixiJS 動態插畫 ---
const PixiIllustration = () => {
  const pixiContainer = useRef(null);
  useEffect(() => {
    if (!pixiContainer.current) return;
    const app = new PIXI.Application();
    let isMounted = true;
    const initPixi = async () => {
      await app.init({ width: 500, height: 500, backgroundAlpha: 0, resolution: window.devicePixelRatio || 1, antialias: true });
      if (!isMounted) { app.destroy(true, { children: true }); return; }
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
        books.push({ sprite: bookGroup, angle: (i * Math.PI) / 2, orbitSpeed: 0.015, rotationSpeed: 0.02 });
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
    return () => { isMounted = false; try { app.destroy(true, { children: true, texture: true, baseTexture: true }); } catch (e) { console.log("Pixi cleanup:", e); } };
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
const HomeView = ({ setCurrentView, onBookClick, user }) => {
  const [stats, setStats] = useState({ donated: 0, claimed: 0 });
  useEffect(() => {
    let currentDonated = 0, currentClaimed = 0;
    const targetDonated = 1254, targetClaimed = 982;
    const interval = setInterval(() => {
      currentDonated += Math.ceil((targetDonated - currentDonated) / 10);
      currentClaimed += Math.ceil((targetClaimed - currentClaimed) / 10);
      setStats({ donated: currentDonated, claimed: currentClaimed });
      if (currentDonated >= targetDonated && currentClaimed >= targetClaimed) clearInterval(interval);
    }, 50);
    return () => clearInterval(interval);
  }, []);

  return (
    <div className="animate-in fade-in duration-500">
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
            <button onClick={() => setCurrentView('search')} className="flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-4 rounded-xl text-lg font-bold hover:bg-emerald-700 hover:-translate-y-1 shadow-xl shadow-emerald-200 transition-all duration-300">
              <Search className="w-5 h-5" /> 立即尋書
            </button>
            <button onClick={() => setCurrentView(user ? 'donate' : 'auth')} className="flex items-center justify-center gap-2 bg-white text-emerald-700 border-2 border-emerald-600 px-8 py-4 rounded-xl text-lg font-bold hover:bg-emerald-50 hover:-translate-y-1 shadow-lg shadow-slate-100 transition-all duration-300">
              <Share2 className="w-5 h-5" /> 我要捐贈
            </button>
          </div>
        </div>
        <div className="relative flex justify-center lg:justify-end">
          <PixiIllustration />
        </div>
      </main>

      <section className="max-w-5xl mx-auto px-6 relative -mt-12 mb-20 z-20">
        <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-8 flex flex-col md:flex-row justify-around items-center gap-8 divide-y md:divide-y-0 md:divide-x divide-slate-100">
          <div className="text-center w-full">
            <p className="text-slate-500 font-medium mb-2">目前已累計捐贈書籍</p>
            <div className="text-5xl font-black text-emerald-600 font-mono tracking-tight">{stats.donated.toLocaleString()} <span className="text-xl text-slate-400 font-sans">本</span></div>
          </div>
          <div className="text-center w-full pt-8 md:pt-0">
            <p className="text-slate-500 font-medium mb-2">成功傳遞知識次數</p>
            <div className="text-5xl font-black text-amber-500 font-mono tracking-tight">{stats.claimed.toLocaleString()} <span className="text-xl text-slate-400 font-sans">次</span></div>
          </div>
        </div>
      </section>

      <section className="max-w-7xl mx-auto px-6 pb-32">
        <div className="flex justify-between items-end mb-8">
          <div>
            <h2 className="text-3xl font-bold text-slate-900 mb-2">最新待領取書籍</h2>
            <p className="text-slate-500">搶先發現校園內剛上架的二手好書</p>
          </div>
          <button onClick={() => setCurrentView('search')} className="hidden sm:flex items-center gap-1 text-emerald-600 font-semibold hover:text-emerald-800 transition-colors">
            查看全部 <ChevronRight className="w-5 h-5" />
          </button>
        </div>

        <div className="flex overflow-x-auto gap-6 pb-8 snap-x snap-mandatory" style={{ scrollbarWidth: 'none', msOverflowStyle: 'none' }}>
          {MOCK_BOOKS.map((book) => (
            <div key={book.id} onClick={() => onBookClick(book)} className="min-w-[240px] max-w-[240px] flex-shrink-0 bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-emerald-100 hover:-translate-y-2 transition-all duration-300 snap-start group cursor-pointer">
              <div className="relative w-full h-64 mb-4 rounded-xl overflow-hidden bg-slate-100">
                <img src={book.coverUrl} alt={book.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                {book.status === 'available' && (
                  <div className="absolute top-3 left-3 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                    <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span className="text-xs font-bold text-emerald-700">待領取</span>
                  </div>
                )}
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
const SearchView = ({ onBookClick }) => {
  const [searchTerm, setSearchTerm] = useState('');
  const [isSearchFocused, setIsSearchFocused] = useState(false);
  const [selectedCategories, setSelectedCategories] = useState([]);
  const [showOnlyAvailable, setShowOnlyAvailable] = useState(false);
  const [isMobileFilterOpen, setIsMobileFilterOpen] = useState(false);

  const searchSuggestions = useMemo(() => {
    if (!searchTerm.trim()) return [];
    return MOCK_BOOKS.filter(book => book.title.toLowerCase().includes(searchTerm.toLowerCase())).map(book => book.title).slice(0, 5);
  }, [searchTerm]);

  const filteredBooks = useMemo(() => {
    return MOCK_BOOKS.filter(book => {
      const matchSearch = book.title.toLowerCase().includes(searchTerm.toLowerCase()) || book.author.toLowerCase().includes(searchTerm.toLowerCase());
      const matchCategory = selectedCategories.length === 0 || selectedCategories.includes(book.category);
      const matchStatus = showOnlyAvailable ? book.status === 'available' : true;
      return matchSearch && matchCategory && matchStatus;
    });
  }, [searchTerm, selectedCategories, showOnlyAvailable]);

  const toggleCategory = (category) => setSelectedCategories(prev => prev.includes(category) ? prev.filter(c => c !== category) : [...prev, category]);

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8 flex flex-col md:flex-row gap-8 animate-in fade-in duration-500">
      <div className="md:hidden flex justify-between items-center mb-4">
        <h1 className="text-2xl font-bold text-slate-900">尋書大廳</h1>
        <button onClick={() => setIsMobileFilterOpen(true)} className="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-slate-600 font-medium">
          <Filter className="w-4 h-4" /> 篩選
        </button>
      </div>

      <aside className={`fixed inset-0 z-50 bg-white md:bg-transparent md:static md:block md:w-64 flex-shrink-0 transition-transform duration-300 ease-in-out ${isMobileFilterOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'}`}>
        <div className="h-full overflow-y-auto p-6 md:p-0 md:sticky md:top-24">
          <div className="flex justify-between items-center md:hidden mb-6">
            <h2 className="text-xl font-bold text-slate-900">篩選條件</h2>
            <button onClick={() => setIsMobileFilterOpen(false)} className="p-2 text-slate-400"><X className="w-6 h-6" /></button>
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
          <div className="md:hidden mt-8"><button onClick={() => setIsMobileFilterOpen(false)} className="w-full bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg">套用篩選</button></div>
        </div>
      </aside>

      <div className="flex-1">
        <h1 className="hidden md:block text-3xl font-bold text-slate-900 mb-6">尋書大廳</h1>
        <div className="relative mb-8 z-40">
          <div className={`relative flex items-center bg-white rounded-2xl border-2 transition-all duration-300 shadow-sm ${isSearchFocused ? 'border-emerald-500 shadow-emerald-100 ring-4 ring-emerald-50' : 'border-slate-200 hover:border-slate-300'}`}>
            <Search className={`w-6 h-6 ml-4 ${isSearchFocused ? 'text-emerald-500' : 'text-slate-400'}`} />
            <input type="text" placeholder="搜尋書名、作者..." className="w-full py-4 px-4 bg-transparent outline-none text-slate-700 text-lg placeholder:text-slate-400 font-medium" value={searchTerm} onChange={(e) => setSearchTerm(e.target.value)} onFocus={() => setIsSearchFocused(true)} onBlur={() => setTimeout(() => setIsSearchFocused(false), 200)} />
            {searchTerm && <button onClick={() => setSearchTerm('')} className="p-2 mr-2 text-slate-400"><X className="w-5 h-5" /></button>}
          </div>
          {isSearchFocused && searchSuggestions.length > 0 && (
            <div className="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-100 rounded-xl shadow-xl overflow-hidden divide-y divide-slate-50">
              {searchSuggestions.map((suggestion, index) => (
                <div key={index} className="px-6 py-3 hover:bg-emerald-50 text-slate-700 font-medium cursor-pointer" onClick={() => setSearchTerm(suggestion)}>
                  <Search className="inline w-4 h-4 mr-3 text-slate-400" />{suggestion}
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
              <div key={book.id} onClick={() => onBookClick(book)} className="group relative bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-emerald-900/10 hover:-translate-y-2 transition-all duration-300 cursor-pointer flex flex-col h-full">
                <div className="relative w-full h-64 bg-slate-100 overflow-hidden flex-shrink-0">
                  <img src={book.coverUrl} alt={book.title} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" />
                  <div className="absolute top-3 left-3 bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-2 shadow-sm">
                    <span className={`w-2.5 h-2.5 rounded-full ${book.status === 'available' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'}`}></span>
                    <span className={`text-xs font-bold ${book.status === 'available' ? 'text-emerald-700' : 'text-amber-700'}`}>{book.status === 'available' ? '待領取' : '已預約'}</span>
                  </div>
                  <div className="absolute inset-0 bg-slate-900/80 backdrop-blur-sm p-6 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end">
                    <p className="text-white text-sm leading-relaxed line-clamp-4 font-medium mb-2">{book.description}</p>
                    <div className="flex items-center gap-2 text-emerald-300 text-sm"><BookOpen className="w-4 h-4" /><span>點擊查看詳情</span></div>
                  </div>
                </div>
                <div className="p-5 flex flex-col flex-grow bg-white relative z-10">
                  <div className="mb-1"><span className="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">{book.category}</span></div>
                  <h3 className="font-bold text-slate-900 text-lg leading-tight mb-1 line-clamp-2" title={book.title}>{book.title}</h3>
                  <p className="text-sm text-slate-500 mb-4 line-clamp-1">{book.author}</p>
                  <div className="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div className="flex items-center gap-2">
                      <div className="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">{book.donor.charAt(0)}</div>
                      <span className="text-sm font-medium text-slate-600">{book.donor}</span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="w-full py-20 flex flex-col items-center justify-center text-center bg-white rounded-3xl border border-dashed border-slate-300">
            <div className="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-6"><Search className="w-10 h-10 text-slate-400" /></div>
            <h3 className="text-xl font-bold text-slate-800 mb-2">找不到符合的書籍</h3>
          </div>
        )}
      </div>
    </div>
  );
};

// --- 視圖 3：書籍詳情頁 (BookDetailView) ---
const BookDetailView = ({ book, onBack }) => {
  const [showContact, setShowContact] = useState(false);
  const [newComment, setNewComment] = useState('');
  const [comments, setComments] = useState(book.comments || []);
  const [isLiked, setIsLiked] = useState(false);
  const [isSaved, setIsSaved] = useState(false);

  useEffect(() => {
    window.scrollTo(0, 0);
    setShowContact(false);
    setComments(book.comments || []);
  }, [book]);

  const handleAddComment = () => {
    if (!newComment.trim()) return;
    setComments([...comments, { id: Date.now(), user: '訪客', date: new Date().toLocaleDateString(), text: newComment }]);
    setNewComment('');
  };

  if (!book) return null;

  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 py-8 animate-in slide-in-from-bottom-4 duration-500">
      <button onClick={onBack} className="flex items-center gap-2 text-slate-500 hover:text-emerald-600 font-medium mb-8 transition-colors">
        <ArrowLeft className="w-5 h-5" /> 返回列表
      </button>

      <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden flex flex-col lg:flex-row">
        <div className="w-full lg:w-2/5 bg-slate-100 relative">
          <img src={book.coverUrl} alt={book.title} className="w-full h-full object-cover max-h-[500px] lg:max-h-none lg:absolute inset-0" />
          <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2 shadow-md">
            <span className={`w-3 h-3 rounded-full ${book.status === 'available' ? 'bg-emerald-500 animate-pulse' : 'bg-amber-400'}`}></span>
            <span className={`text-sm font-bold ${book.status === 'available' ? 'text-emerald-700' : 'text-amber-700'}`}>{book.status === 'available' ? '開放領取中' : '已被預約'}</span>
          </div>
        </div>

        <div className="w-full lg:w-3/5 p-8 lg:p-12 flex flex-col">
          <div className="mb-6">
            <div className="inline-block px-3 py-1 mb-4 bg-emerald-50 text-emerald-700 font-bold text-sm rounded-lg border border-emerald-100">{book.category}</div>
            <h1 className="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-2 leading-tight">{book.title}</h1>
            <p className="text-lg text-slate-500 font-medium">{book.author}</p>
          </div>

          <div className="grid grid-cols-2 gap-y-4 gap-x-8 p-6 bg-slate-50 rounded-2xl mb-8">
            <div><p className="text-sm font-medium text-slate-400 flex items-center gap-1.5 mb-1"><Hash className="w-4 h-4"/> ISBN</p><p className="font-semibold text-slate-800">{book.isbn || '無資料'}</p></div>
            <div><p className="text-sm font-medium text-slate-400 flex items-center gap-1.5 mb-1"><Building2 className="w-4 h-4"/> 出版社</p><p className="font-semibold text-slate-800">{book.publisher || '無資料'}</p></div>
            <div><p className="text-sm font-medium text-slate-400 flex items-center gap-1.5 mb-1"><Calendar className="w-4 h-4"/> 出版年月</p><p className="font-semibold text-slate-800">{book.publishDate || '無資料'}</p></div>
            <div><p className="text-sm font-medium text-slate-400 flex items-center gap-1.5 mb-1"><BookOpen className="w-4 h-4"/> 書況簡述</p><p className="font-semibold text-slate-800 line-clamp-1" title={book.description}>{book.description}</p></div>
          </div>

          <div className="flex gap-4 mb-10 pb-10 border-b border-slate-100">
            <button onClick={() => setIsLiked(!isLiked)} className={`flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold border-2 transition-all ${isLiked ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'}`}>
              <ThumbsUp className={`w-5 h-5 ${isLiked ? 'fill-emerald-600' : ''}`} /> 點讚 ({book.likes + (isLiked ? 1 : 0)})
            </button>
            <button onClick={() => setIsSaved(!isSaved)} className={`flex-1 flex items-center justify-center gap-2 py-3 rounded-xl font-bold border-2 transition-all ${isSaved ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300'}`}>
              <Heart className={`w-5 h-5 ${isSaved ? 'fill-amber-500' : ''}`} /> 收藏 ({book.saves + (isSaved ? 1 : 0)})
            </button>
          </div>

          <div className="bg-emerald-50/50 p-6 rounded-2xl border border-emerald-100">
            <div className="flex items-center justify-between mb-4">
              <div className="flex items-center gap-3">
                <div className="w-12 h-12 bg-emerald-200 rounded-full flex items-center justify-center text-emerald-800 font-bold text-xl">{book.donor.charAt(0)}</div>
                <div><p className="text-sm font-medium text-emerald-600/80">知識傳遞者 (捐贈人)</p><p className="text-lg font-bold text-slate-800">{book.donor}</p></div>
              </div>
            </div>
            
            {showContact ? (
              <div className="bg-white p-4 rounded-xl border border-emerald-200 flex items-center gap-3 animate-in fade-in zoom-in duration-200">
                <Mail className="w-5 h-5 text-emerald-600" /><a href={`mailto:${book.donorContact}`} className="text-emerald-700 font-bold hover:underline">{book.donorContact}</a>
              </div>
            ) : (
              <button onClick={() => setShowContact(true)} disabled={book.status !== 'available'} className={`w-full py-4 rounded-xl font-bold text-lg flex items-center justify-center gap-2 shadow-lg transition-all ${book.status === 'available' ? 'bg-emerald-600 text-white hover:bg-emerald-700 hover:-translate-y-1 shadow-emerald-200' : 'bg-slate-200 text-slate-400 cursor-not-allowed shadow-none'}`}>
                <Mail className="w-5 h-5" />{book.status === 'available' ? '我想領取，取得聯絡方式' : '此書目前無法領取'}
              </button>
            )}
          </div>
        </div>
      </div>

      <div className="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div className="lg:col-span-1 bg-white p-8 rounded-3xl shadow-sm border border-slate-100 h-fit">
          <h3 className="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2"><Clock className="w-6 h-6 text-emerald-500" /> 知識足跡</h3>
          <div className="space-y-6 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
            {book.history && book.history.map((event) => (
              <div key={event.id} className="relative flex items-start justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                <div className="flex items-center justify-center w-5 h-5 rounded-full border-4 border-white bg-emerald-500 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10" />
                <div className="w-[calc(100%-2.5rem)] md:w-[calc(50%-2rem)] p-4 rounded-xl border border-slate-100 bg-slate-50 shadow-sm">
                  <div className="flex items-center justify-between mb-1"><div className="font-bold text-slate-800 text-sm">{event.date.split(' ')[0]}</div></div>
                  <div className="text-slate-600 text-sm leading-snug">{event.action}</div>
                </div>
              </div>
            ))}
          </div>
        </div>

        <div className="lg:col-span-2 bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
          <h3 className="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2"><MessageSquare className="w-6 h-6 text-emerald-500" /> 書況詢問與留言</h3>
          <div className="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-4 custom-scrollbar">
            {comments.length > 0 ? comments.map((comment) => (
              <div key={comment.id} className={`flex gap-4 ${comment.isDonor ? 'flex-row-reverse' : ''}`}>
                <div className={`w-10 h-10 rounded-full flex items-center justify-center font-bold flex-shrink-0 ${comment.isDonor ? 'bg-emerald-200 text-emerald-800' : 'bg-slate-200 text-slate-600'}`}>{comment.user.charAt(0)}</div>
                <div className={`flex flex-col ${comment.isDonor ? 'items-end' : 'items-start'}`}>
                  <div className="flex items-baseline gap-2 mb-1">
                    <span className="font-bold text-sm text-slate-700">{comment.user}</span>
                    {comment.isDonor && <span className="text-[10px] bg-emerald-100 text-emerald-700 px-1.5 py-0.5 rounded font-bold">捐贈者</span>}
                    <span className="text-xs text-slate-400">{comment.date}</span>
                  </div>
                  <div className={`px-4 py-3 rounded-2xl max-w-lg ${comment.isDonor ? 'bg-emerald-600 text-white rounded-tr-sm' : 'bg-slate-100 text-slate-700 rounded-tl-sm'}`}>{comment.text}</div>
                </div>
              </div>
            )) : (<p className="text-center text-slate-400 py-8 italic">目前還沒有人留言，來搶頭香吧！</p>)}
          </div>
          <div className="flex gap-4">
             <div className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500 flex-shrink-0"><User className="w-5 h-5"/></div>
             <div className="flex-1 flex gap-2">
               <input type="text" value={newComment} onChange={(e) => setNewComment(e.target.value)} onKeyDown={(e) => e.key === 'Enter' && handleAddComment()} placeholder="詢問書況或面交地點..." className="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 transition-all" />
               <button onClick={handleAddComment} disabled={!newComment.trim()} className="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-slate-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">送出</button>
             </div>
          </div>
        </div>
      </div>
    </div>
  );
};


// --- 視圖 4：登入與註冊頁 (AuthView) ---
const AuthView = ({ setCurrentView, setUser }) => {
  const [isLogin, setIsLogin] = useState(true);
  const [formData, setFormData] = useState({ email: '', password: '', studentId: '', location: '' });
  const [errors, setErrors] = useState({});

  useEffect(() => {
    setErrors({});
    setFormData({ email: '', password: '', studentId: '', location: '' });
  }, [isLogin]);

  const validateField = (name, value) => {
    let newErrors = { ...errors };
    switch (name) {
      case 'email':
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!value) newErrors.email = '請輸入 Email 信箱';
        else if (!emailRegex.test(value)) newErrors.email = 'Email 格式不正確';
        else delete newErrors.email;
        break;
      case 'password':
        if (!value) newErrors.password = '請輸入密碼';
        else if (value.length < 6) newErrors.password = '密碼長度至少需 6 個字元';
        else delete newErrors.password;
        break;
      case 'studentId':
        if (!isLogin) {
          const idRegex = /^[a-zA-Z0-9]{7,10}$/;
          if (!value) newErrors.studentId = '請輸入學號';
          else if (!idRegex.test(value)) newErrors.studentId = '學號格式不正確 (請輸入7-10碼英數字)';
          else delete newErrors.studentId;
        }
        break;
      case 'location':
        if (!isLogin) {
          if (!value.trim()) newErrors.location = '請填寫主要面交地點';
          else delete newErrors.location;
        }
        break;
      default:
        break;
    }
    setErrors(newErrors);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    validateField(name, value);
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    validateField('email', formData.email);
    validateField('password', formData.password);
    if (!isLogin) {
      validateField('studentId', formData.studentId);
      validateField('location', formData.location);
    }
    const currentErrors = {};
    if (!formData.email) currentErrors.email = '請輸入 Email 信箱';
    if (!formData.password) currentErrors.password = '請輸入密碼';
    if (!isLogin && !formData.studentId) currentErrors.studentId = '請輸入學號';
    if (!isLogin && !formData.location) currentErrors.location = '請填寫主要面交地點';

    if (Object.keys(currentErrors).length === 0 && Object.keys(errors).length === 0) {
      alert(isLogin ? '登入成功！歡迎回來。' : '註冊成功！準備開始流動知識。');
      setUser({ 
        name: isLogin ? '王同學' : '新同學', 
        studentId: formData.studentId || 's1101234',
        email: formData.email,
        likes: 128, 
        dislikes: 3
      });
      setCurrentView('dashboard');
    } else {
      setErrors(prev => ({...prev, ...currentErrors}));
    }
  };

  return (
    <div className="min-h-[calc(100vh-80px)] flex animate-in fade-in duration-500">
      <div className="hidden lg:flex w-1/2 relative bg-emerald-900 overflow-hidden">
        <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&q=80&w=1000" alt="Library" className="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay" />
        <div className="relative z-10 flex flex-col justify-center px-16 xl:px-24 h-full">
          <BookOpen className="w-16 h-16 text-emerald-400 mb-8" />
          <h1 className="text-4xl xl:text-5xl font-extrabold text-white leading-tight mb-6">讓每本書都能<br/>遇見下一個讀者。</h1>
          <p className="text-lg text-emerald-100/80 leading-relaxed max-w-md">加入書活平台，建立你的專屬知識存摺。只需使用學校 Email 與學號註冊，即可輕鬆捐贈與尋找所需的教材資源。</p>
        </div>
      </div>
      <div className="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 xl:p-24 bg-white">
        <div className="w-full max-w-md">
          <button onClick={() => setCurrentView('home')} className="flex items-center gap-2 text-slate-400 hover:text-emerald-600 font-medium mb-8 transition-colors"><ArrowLeft className="w-4 h-4" /> 返回首頁</button>
          <h2 className="text-3xl font-bold text-slate-900 mb-2">{isLogin ? '歡迎回來' : '建立新帳號'}</h2>
          <p className="text-slate-500 mb-8">{isLogin ? '請輸入您的帳號密碼以繼續' : '加入書活，與校園社群分享知識'}</p>
          <div className="flex p-1 bg-slate-100 rounded-xl mb-8">
            <button onClick={() => setIsLogin(true)} className={`flex-1 py-2.5 text-sm font-bold rounded-lg transition-all ${isLogin ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>登入</button>
            <button onClick={() => setIsLogin(false)} className={`flex-1 py-2.5 text-sm font-bold rounded-lg transition-all ${!isLogin ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>註冊</button>
          </div>
          <form onSubmit={handleSubmit} className="space-y-5">
            {!isLogin && (
              <div>
                <label className="block text-sm font-bold text-slate-700 mb-1.5">學號 <span className="text-red-500">*</span></label>
                <input type="text" name="studentId" value={formData.studentId} onChange={handleChange} placeholder="例如: s1101234" className={`w-full px-4 py-3 rounded-xl border bg-slate-50 outline-none transition-all ${errors.studentId ? 'border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100'}`} />
                {errors.studentId && <p className="text-red-500 text-sm mt-1.5 flex items-center gap-1"><AlertCircle className="w-3.5 h-3.5"/>{errors.studentId}</p>}
              </div>
            )}
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-1.5">Email 信箱 <span className="text-red-500">*</span></label>
              <input type="email" name="email" value={formData.email} onChange={handleChange} placeholder="school@example.edu.tw" className={`w-full px-4 py-3 rounded-xl border bg-slate-50 outline-none transition-all ${errors.email ? 'border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100'}`} />
              {errors.email && <p className="text-red-500 text-sm mt-1.5 flex items-center gap-1"><AlertCircle className="w-3.5 h-3.5"/>{errors.email}</p>}
            </div>
            <div>
              <label className="block text-sm font-bold text-slate-700 mb-1.5 flex justify-between"><span>密碼 <span className="text-red-500">*</span></span>{isLogin && <a href="#" className="text-emerald-600 font-medium hover:underline">忘記密碼？</a>}</label>
              <input type="password" name="password" value={formData.password} onChange={handleChange} placeholder="••••••••" className={`w-full px-4 py-3 rounded-xl border bg-slate-50 outline-none transition-all ${errors.password ? 'border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100'}`} />
              {errors.password && <p className="text-red-500 text-sm mt-1.5 flex items-center gap-1"><AlertCircle className="w-3.5 h-3.5"/>{errors.password}</p>}
            </div>
            {!isLogin && (
              <div>
                <label className="block text-sm font-bold text-slate-700 mb-1.5">主要面交地點 <span className="text-red-500">*</span></label>
                <input type="text" name="location" value={formData.location} onChange={handleChange} placeholder="例如: 圖書館大廳、資傳系辦" className={`w-full px-4 py-3 rounded-xl border bg-slate-50 outline-none transition-all ${errors.location ? 'border-red-300 focus:border-red-500 focus:ring-2 focus:ring-red-100' : 'border-slate-200 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100'}`} />
                {errors.location && <p className="text-red-500 text-sm mt-1.5 flex items-center gap-1"><AlertCircle className="w-3.5 h-3.5"/>{errors.location}</p>}
              </div>
            )}
            <button type="submit" className="w-full bg-emerald-600 text-white font-bold text-lg py-3.5 rounded-xl mt-4 hover:bg-emerald-700 hover:-translate-y-0.5 shadow-lg shadow-emerald-200 transition-all duration-300 flex items-center justify-center gap-2">
              {isLogin ? '登入系統' : '註冊帳號'} <ChevronRight className="w-5 h-5" />
            </button>
          </form>
        </div>
      </div>
    </div>
  );
};

// --- 視圖 5：個人管理後台 (DashboardView) ---
const DashboardView = ({ user, setUser, setCurrentView, onBookClick }) => {
  const [activeTab, setActiveTab] = useState('manage');
  
  const [myBooks, setMyBooks] = useState([
    { id: 101, title: '資料庫系統原理', coverUrl: 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&q=80&w=150', date: '2026/04/01', status: 'available' },
    { id: 102, title: '計算機組織與設計', coverUrl: 'https://images.unsplash.com/photo-1516979187457-637abb4f9353?auto=format&fit=crop&q=80&w=150', date: '2026/03/15', status: 'reserved' },
    { id: 103, title: 'Python 機器學習', coverUrl: 'https://images.unsplash.com/photo-1555662800-87311b3a51d9?auto=format&fit=crop&q=80&w=150', date: '2026/02/20', status: 'shipped' },
  ]);

  const handleStatusToggle = (id, newStatus) => setMyBooks(myBooks.map(b => b.id === id ? { ...b, status: newStatus } : b));
  const handleDelete = (id) => { if (confirm('確定要從資料庫中刪除這筆捐贈紀錄嗎？')) setMyBooks(myBooks.filter(b => b.id !== id)); };
  const handleLogout = () => { setUser(null); setCurrentView('home'); };

  return (
    <div className="max-w-7xl mx-auto px-4 sm:px-6 py-8 animate-in fade-in duration-500">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-3xl font-extrabold text-slate-900">個人管理後台</h1>
          <p className="text-slate-500 mt-1">歡迎回來，{user.name} ({user.studentId})</p>
        </div>
        <button onClick={handleLogout} className="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg font-bold hover:bg-red-100 transition-colors">
          <LogOut className="w-4 h-4" /> 登出
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
          <div className="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center"><ThumbsUp className="w-7 h-7"/></div>
          <div><p className="text-slate-500 font-bold mb-1">信用分數 (獲讚數)</p><p className="text-3xl font-black text-slate-800">{user.likes}</p></div>
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
          <div className="w-14 h-14 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center"><Heart className="w-7 h-7"/></div>
          <div><p className="text-slate-500 font-bold mb-1">已收藏書籍</p><p className="text-3xl font-black text-slate-800">4</p></div>
        </div>
        <div className="bg-white p-6 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-5">
          <div className="w-14 h-14 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center"><BookOpen className="w-7 h-7"/></div>
          <div><p className="text-slate-500 font-bold mb-1">累積捐出書籍</p><p className="text-3xl font-black text-slate-800">{myBooks.length}</p></div>
        </div>
      </div>

      <div className="flex gap-2 p-1 bg-slate-100 rounded-xl w-fit mb-6">
        <button onClick={() => setActiveTab('manage')} className={`px-6 py-2.5 text-sm font-bold rounded-lg transition-all ${activeTab === 'manage' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>我的捐贈書籍</button>
        <button onClick={() => setActiveTab('saved')} className={`px-6 py-2.5 text-sm font-bold rounded-lg transition-all ${activeTab === 'saved' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500 hover:text-slate-700'}`}>收藏清單</button>
      </div>

      {activeTab === 'manage' && (
        <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-left border-collapse min-w-[700px]">
              <thead>
                <tr className="bg-slate-50 text-slate-500 text-sm border-b border-slate-200">
                  <th className="p-5 font-bold">封面與書名</th><th className="p-5 font-bold">上傳日期</th>
                  <th className="p-5 font-bold">目前狀態</th><th className="p-5 font-bold text-right">快速操作</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {myBooks.map(book => (
                  <tr key={book.id} className="hover:bg-slate-50/50 transition-colors group">
                    <td className="p-5 flex items-center gap-4">
                      <img src={book.coverUrl} className="w-12 h-16 object-cover rounded border border-slate-100" alt={book.title}/>
                      <span className="font-bold text-slate-800 text-base">{book.title}</span>
                    </td>
                    <td className="p-5 text-slate-500 font-medium">{book.date}</td>
                    <td className="p-5">
                      <span className={`px-3 py-1.5 text-xs font-bold rounded-md flex items-center w-fit gap-1.5 ${book.status === 'available' ? 'bg-emerald-100 text-emerald-700' : book.status === 'reserved' ? 'bg-amber-100 text-amber-700' : book.status === 'paused' ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-700'}`}>
                        {book.status === 'available' ? <><div className="w-1.5 h-1.5 rounded-full bg-emerald-500"/>待領取</> : book.status === 'reserved' ? <><div className="w-1.5 h-1.5 rounded-full bg-amber-500"/>已預約</> : book.status === 'paused' ? <><PauseCircle className="w-3 h-3"/>已暫停</> : <><CheckCircle2 className="w-3 h-3"/>已送出</>}
                      </span>
                    </td>
                    <td className="p-5">
                      <div className="flex items-center justify-end gap-2 opacity-100 lg:opacity-0 lg:group-hover:opacity-100 transition-opacity">
                        <button className="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="編輯內容"><Edit className="w-4 h-4"/></button>
                        {book.status !== 'shipped' && (
                          <>
                            <button onClick={() => handleStatusToggle(book.id, book.status === 'paused' ? 'available' : 'paused')} className="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title={book.status === 'paused' ? '恢復捐贈' : '暫停捐贈'}>
                              {book.status === 'paused' ? <BookOpen className="w-4 h-4"/> : <PauseCircle className="w-4 h-4"/>}
                            </button>
                            <button onClick={() => handleStatusToggle(book.id, 'shipped')} className="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="標記為已送出"><Send className="w-4 h-4"/></button>
                          </>
                        )}
                        <button onClick={() => handleDelete(book.id)} className="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="刪除紀錄"><Trash2 className="w-4 h-4"/></button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      )}

      {activeTab === 'saved' && (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {MOCK_BOOKS.slice(0, 4).map(book => (
            <div key={book.id} onClick={() => onBookClick(book)} className="group bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all cursor-pointer flex flex-col">
              <div className="relative w-full h-48 bg-slate-100 overflow-hidden">
                <img src={book.coverUrl} className="w-full h-full object-cover" alt={book.title} />
                <div className="absolute top-2 right-2 p-1.5 bg-white/90 rounded-full text-amber-500 shadow-sm"><Heart className="w-4 h-4 fill-current" /></div>
              </div>
              <div className="p-4 flex-1 flex flex-col">
                <h3 className="font-bold text-slate-900 line-clamp-1 mb-1">{book.title}</h3>
                <span className={`mt-auto w-fit px-2 py-1 text-xs font-bold rounded-md ${book.status === 'available' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500'}`}>
                  {book.status === 'available' ? '此書依然可領取' : '已被他人預約'}
                </span>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

// --- 視圖 6：我要捐書表單 (DonateView) ---
const DonateView = ({ setCurrentView }) => {
  const [isbn, setIsbn] = useState('');
  const [isFetching, setIsFetching] = useState(false);
  const [dragActive, setDragActive] = useState(false);
  const [imagePreview, setImagePreview] = useState(null);
  
  const [formData, setFormData] = useState({
    title: '', author: '', category: CATEGORIES[0], description: ''
  });

  const handleIsbnFetch = async () => {
    if (!isbn.trim()) return alert('請先輸入 ISBN 碼');
    setIsFetching(true);
    try {
      const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
      const data = await response.json();
      if (data.items && data.items.length > 0) {
        const bookInfo = data.items[0].volumeInfo;
        setFormData(prev => ({
          ...prev, title: bookInfo.title || '', author: bookInfo.authors ? bookInfo.authors.join(', ') : '',
        }));
        if (!imagePreview && bookInfo.imageLinks?.thumbnail) {
          setImagePreview(bookInfo.imageLinks.thumbnail.replace('http:', 'https:'));
        }
      } else { alert('找不到此 ISBN 的相關書籍資訊，請手動填寫。'); }
    } catch (error) {
      console.error("API Fetch Error:", error);
      alert('自動抓取失敗，請確認網路狀態或手動填寫。');
    } finally { setIsFetching(false); }
  };

  const handleChange = (e) => { const { name, value } = e.target; setFormData(prev => ({ ...prev, [name]: value })); };

  const handleDrag = (e) => { e.preventDefault(); e.stopPropagation(); if (e.type === "dragenter" || e.type === "dragover") { setDragActive(true); } else if (e.type === "dragleave") { setDragActive(false); } };
  const handleDrop = (e) => { e.preventDefault(); e.stopPropagation(); setDragActive(false); if (e.dataTransfer.files && e.dataTransfer.files[0]) { handleFile(e.dataTransfer.files[0]); } };
  const handleFileChange = (e) => { if (e.target.files && e.target.files[0]) { handleFile(e.target.files[0]); } };

  const handleFile = (file) => {
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader(); reader.onload = (e) => setImagePreview(e.target.result); reader.readAsDataURL(file);
    } else { alert('請上傳圖片格式的檔案 (如 JPG, PNG)'); }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    if (!formData.title) return alert('請填寫書名');
    if (!imagePreview) return alert('請上傳書籍照片');
    alert('書籍上架成功！感謝您的分享！');
    setCurrentView('dashboard');
  };

  return (
    <div className="max-w-4xl mx-auto px-4 sm:px-6 py-8 animate-in fade-in duration-500">
      <button onClick={() => setCurrentView('dashboard')} className="flex items-center gap-2 text-slate-500 hover:text-emerald-600 font-medium mb-6 transition-colors">
        <ArrowLeft className="w-5 h-5" /> 返回管理後台
      </button>

      <div className="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
        <div className="bg-emerald-600 px-8 py-10 text-white">
          <h1 className="text-3xl font-extrabold mb-2">我要捐書</h1>
          <p className="text-emerald-100 text-lg">分享您的閒置書籍，讓知識流向需要的人。</p>
        </div>

        <div className="p-8 lg:p-12">
          <div className="mb-10 p-6 bg-slate-50 border border-slate-200 rounded-2xl">
            <label className="block text-sm font-bold text-slate-700 mb-2 flex items-center gap-2">
              <Search className="w-4 h-4 text-emerald-600" /> ISBN 自動抓取 (推薦)
            </label>
            <div className="flex gap-3">
              <input type="text" value={isbn} onChange={(e) => setIsbn(e.target.value)} placeholder="請輸入書籍背面的 ISBN 13 碼" className="flex-1 px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all" />
              <button onClick={handleIsbnFetch} disabled={isFetching || !isbn} className="bg-emerald-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center min-w-[120px]">
                {isFetching ? <Loader2 className="w-5 h-5 animate-spin" /> : '自動填入'}
              </button>
            </div>
            <p className="text-xs text-slate-500 mt-2">系統將串接 Google Books API 自動為您帶入書名與作者。</p>
          </div>

          <form onSubmit={handleSubmit} className="space-y-8">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div className="space-y-6">
                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-2">書名 <span className="text-red-500">*</span></label>
                  <input type="text" name="title" value={formData.title} onChange={handleChange} required placeholder="請輸入完整書名" className="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all bg-white" />
                </div>
                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-2">作者</label>
                  <input type="text" name="author" value={formData.author} onChange={handleChange} placeholder="請輸入作者姓名" className="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all bg-white" />
                </div>
                <div>
                  <label className="block text-sm font-bold text-slate-700 mb-2">書籍類別 <span className="text-red-500">*</span></label>
                  <select name="category" value={formData.category} onChange={handleChange} className="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all bg-white font-medium text-slate-700 cursor-pointer appearance-none" style={{ backgroundImage: `url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e")`, backgroundRepeat: 'no-repeat', backgroundPosition: 'right 1rem center', backgroundSize: '1em' }}>
                    {CATEGORIES.map(cat => ( <option key={cat} value={cat}>{cat}</option> ))}
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-sm font-bold text-slate-700 mb-2">書籍實體照片 <span className="text-red-500">*</span></label>
                <div className={`relative w-full h-full min-h-[220px] rounded-2xl border-2 border-dashed flex flex-col items-center justify-center p-6 text-center transition-all cursor-pointer overflow-hidden ${dragActive ? 'border-emerald-500 bg-emerald-50' : 'border-slate-300 hover:border-emerald-400 bg-slate-50'}`} onDragEnter={handleDrag} onDragLeave={handleDrag} onDragOver={handleDrag} onDrop={handleDrop}>
                  <input type="file" accept="image/*" onChange={handleFileChange} className="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20" />
                  {imagePreview ? (
                    <img src={imagePreview} alt="Preview" className="absolute inset-0 w-full h-full object-contain p-2 z-10 bg-slate-50" />
                  ) : (
                    <div className="z-10 pointer-events-none">
                      <div className="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 text-emerald-600"><UploadCloud className="w-8 h-8" /></div>
                      <p className="text-slate-700 font-bold text-lg mb-1">點擊或拖曳圖片至此</p><p className="text-slate-500 text-sm">支援 JPG, PNG 格式 (最大 5MB)</p>
                    </div>
                  )}
                  {imagePreview && (
                    <div className="absolute inset-0 bg-black/50 opacity-0 hover:opacity-100 transition-opacity z-20 flex items-center justify-center pointer-events-none">
                      <p className="text-white font-bold flex items-center gap-2"><UploadCloud className="w-5 h-5"/> 點擊更換圖片</p>
                    </div>
                  )}
                </div>
              </div>
            </div>

            <div>
              <label className="block text-sm font-bold text-slate-700 mb-2 flex justify-between"><span>書況補充描述</span><span className="text-slate-400 font-normal">選填</span></label>
              <textarea name="description" value={formData.description} onChange={handleChange} placeholder="例如：內頁有少許螢光筆劃記，九成新。方便約在圖書館面交..." rows={4} className="w-full px-4 py-3 rounded-xl border border-slate-300 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition-all bg-white resize-y" />
            </div>

            <div className="pt-6 border-t border-slate-100 flex justify-end gap-4">
              <button type="button" onClick={() => setCurrentView('dashboard')} className="px-8 py-3 rounded-xl font-bold text-slate-600 hover:bg-slate-100 transition-colors">取消</button>
              <button type="submit" className="px-10 py-3 rounded-xl font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all hover:-translate-y-0.5">確認送出，發布捐贈</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  );
};


// --- 視圖 7：如何捐贈教學頁 (HowToDonateView) ---
const HowToDonateView = ({ setCurrentView, user }) => {
  return (
    <div className="max-w-6xl mx-auto px-4 sm:px-6 py-12 animate-in fade-in duration-500">
      {/* Header */}
      <div className="text-center mb-16">
        <div className="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-700 font-medium text-sm mb-4">
          <BookPlus className="w-4 h-4" /> 輕鬆三步，讓知識延續
        </div>
        <h1 className="text-4xl md:text-5xl font-extrabold text-slate-900 mb-4">如何發布與管理捐贈？</h1>
        <p className="text-lg text-slate-500 max-w-2xl mx-auto">
          在書活平台，我們盡可能簡化了書籍上架的流程，並提供了完善的後台系統，讓你隨時掌握每一本捐出書籍的流向狀態。
        </p>
      </div>

      {/* 區塊一：發布捐贈過程 */}
      <section className="mb-20">
        <h2 className="text-2xl font-bold text-slate-900 mb-8 flex items-center gap-2">
          <BookPlus className="w-6 h-6 text-emerald-600" /> 我要捐書的發布過程
        </h2>
        
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
          {/* 連接線 (僅在 md 螢幕以上顯示) */}
          <div className="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-emerald-100 z-0"></div>

          {/* 步驟 1 */}
          <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative z-10 flex flex-col items-center text-center">
            <div className="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-lg shadow-emerald-200">1</div>
            <h3 className="text-xl font-bold text-slate-800 mb-3">使用 ISBN 自動抓取</h3>
            <p className="text-slate-500">
              點擊右上角「我要捐書」。只需輸入書本背面的 13 碼 ISBN，系統會自動串接 Google Books 帶入精確的書名與作者，省去打字時間。
            </p>
          </div>

          {/* 步驟 2 */}
          <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative z-10 flex flex-col items-center text-center">
            <div className="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-lg shadow-emerald-200">2</div>
            <h3 className="text-xl font-bold text-slate-800 mb-3">上傳照片與書況</h3>
            <p className="text-slate-500">
              將拍攝好的書籍實體照片拖曳至上傳區，並選擇適合的「書籍類別」。若有筆記或破損，建議在下方的文本框中如實備註。
            </p>
          </div>

          {/* 步驟 3 */}
          <div className="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 relative z-10 flex flex-col items-center text-center">
            <div className="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-lg shadow-emerald-200">3</div>
            <h3 className="text-xl font-bold text-slate-800 mb-3">確認發布上架</h3>
            <p className="text-slate-500">
              按下確認送出後，書籍就會立刻出現在「尋書大廳」中。如果有同學需要，他們便能透過詳情頁取得你的聯絡方式。
            </p>
          </div>
        </div>
      </section>

      {/* 區塊二：後台管理教學 */}
      <section>
        <div className="bg-slate-800 rounded-3xl overflow-hidden shadow-xl">
          <div className="p-8 md:p-12 text-white border-b border-slate-700">
            <h2 className="text-2xl md:text-3xl font-bold flex items-center gap-3 mb-4">
              <Settings className="w-8 h-8 text-emerald-400" /> 已發布書籍的管理與下架
            </h2>
            <p className="text-slate-300 text-lg">
              所有發布的書籍，都可以在登入後的「個人管理後台」中找到。在表格右側的「快速操作」欄位，你可以隨時變更書籍狀態。
            </p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-slate-700">
            
            {/* 操作：修改 */}
            <div className="p-8 hover:bg-slate-700/50 transition-colors">
              <div className="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center text-emerald-400 mb-5">
                <Edit className="w-6 h-6" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2">修改書籍內容</h3>
              <p className="text-slate-400 text-sm leading-relaxed">
                發現打錯字或是想補充更詳細的書況描述？點擊編輯按鈕即可重新進入表單修改資料，不影響已有的按讚與收藏。
              </p>
            </div>

            {/* 操作：暫停/下架 */}
            <div className="p-8 hover:bg-slate-700/50 transition-colors">
              <div className="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center text-amber-400 mb-5">
                <PauseCircle className="w-6 h-6" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2">暫停捐贈 (下架)</h3>
              <p className="text-slate-400 text-sm leading-relaxed">
                如果這本書你暫時還需要使用，或是已經和某位同學口頭約好面交，可以先點擊暫停。書籍將從大廳隱藏，日後可隨時恢復上架。
              </p>
            </div>

            {/* 操作：標記送出 */}
            <div className="p-8 hover:bg-slate-700/50 transition-colors">
              <div className="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center text-blue-400 mb-5">
                <Send className="w-6 h-6" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2">標記為已送出</h3>
              <p className="text-slate-400 text-sm leading-relaxed">
                面交完成後，請務必點擊此按鈕！系統會將書籍標示為「已送出」並寫入歷史足跡，同時也會為你的信用分數 +1。
              </p>
            </div>

            {/* 操作：刪除 */}
            <div className="p-8 hover:bg-slate-700/50 transition-colors">
              <div className="w-12 h-12 bg-slate-700 rounded-xl flex items-center justify-center text-red-400 mb-5">
                <Trash2 className="w-6 h-6" />
              </div>
              <h3 className="text-xl font-bold text-white mb-2">永久刪除紀錄</h3>
              <p className="text-slate-400 text-sm leading-relaxed">
                如果書籍不慎遺失或損壞，再也無法捐出，可點擊垃圾桶按鈕。這會將書籍資料從資料庫中徹底移除。
              </p>
            </div>

          </div>
        </div>
      </section>

      {/* CTA Bottom */}
      <div className="mt-16 text-center">
        <button 
          onClick={() => setCurrentView(user ? 'donate' : 'auth')}
          className="bg-emerald-600 text-white font-bold px-10 py-4 rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 hover:-translate-y-1 transition-all"
        >
          {user ? '我了解了，立即前往捐書' : '登入/註冊以開始捐書'}
        </button>
      </div>

    </div>
  );
};


// --- 主應用程式入口 (App Routing Logic) ---
export default function App() {
  const [currentView, setCurrentView] = useState('home');
  const [selectedBook, setSelectedBook] = useState(null);
  const [user, setUser] = useState(null); // 模擬全域登入狀態

  const handleBookClick = (book) => { setSelectedBook(book); setCurrentView('detail'); };
  const handleBackToList = () => { setCurrentView('search'); setSelectedBook(null); };

  return (
    <div className="min-h-screen bg-slate-50 font-sans text-slate-800 selection:bg-emerald-200 selection:text-emerald-900 overflow-x-hidden">
      <Navbar currentView={currentView} setCurrentView={setCurrentView} user={user} />
      
      {currentView === 'home' && <HomeView setCurrentView={setCurrentView} onBookClick={handleBookClick} user={user} />}
      {currentView === 'search' && <SearchView onBookClick={handleBookClick} />}
      {currentView === 'detail' && selectedBook && <BookDetailView book={selectedBook} onBack={handleBackToList} />}
      {currentView === 'auth' && <AuthView setCurrentView={setCurrentView} setUser={setUser} />}
      {currentView === 'dashboard' && user && <DashboardView user={user} setUser={setUser} setCurrentView={setCurrentView} onBookClick={handleBookClick} />}
      {currentView === 'donate' && <DonateView setCurrentView={setCurrentView} />}
      {currentView === 'how-to' && <HowToDonateView setCurrentView={setCurrentView} user={user} />}

      <style dangerouslySetInnerHTML={{__html: `
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
      `}} />
    </div>
  );
}