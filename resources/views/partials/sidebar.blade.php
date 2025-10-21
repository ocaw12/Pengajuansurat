<aside class="w-64 bg-amber-100 text-gray-800 flex flex-col justify-between shadow-lg border-r border-amber-200">
    <div>
        <div class="p-6 text-center border-b border-amber-200 bg-amber-50">
            <div class="flex justify-center mb-3">
                <img src="{{ asset('images/logoup45.png') }}" alt="Logo Universitas 45"
                     class="w-16 h-16 rounded-full shadow-md border border-amber-300 bg-white">
            </div>
            <h1 class="text-lg font-extrabold leading-tight text-gray-900 tracking-wide">SILASMA</h1>
            <p class="text-xs text-gray-700 mt-1 font-medium">Sistem Informasi Layanan Pengajuan Surat Mahasiswa</p>
        </div>

        <nav class="mt-6 font-medium">
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-6 hover:bg-amber-200 transition rounded-r-full">🏠 Dashboard</a>

            <button @click="masterOpen = !masterOpen"
                    class="w-full text-left py-2.5 px-6 flex justify-between items-center hover:bg-amber-200 transition rounded-r-full">
                <span>📚 Data Master</span>
                <svg :class="{'rotate-180': masterOpen}" class="w-4 h-4 transform transition-transform duration-300"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="masterOpen" x-collapse class="pl-10 text-sm space-y-1 mt-1 text-gray-700">
                <a href="#" class="block py-2 hover:text-gray-900 transition">🎓 Data Mahasiswa</a>
                <a href="{{ route('staff.index') }}" class="block py-2 hover:text-gray-900 transition">👩‍💼 Data Staf Jurusan</a>
                <a href="#" class="block py-2 hover:text-gray-900 transition">🧾 Pejabat Berwenang</a>
                <a href="{{ route('fakultas.index') }}" class="block py-2 hover:text-gray-900 transition">🏛️ Data Fakultas</a>
                <a href="{{ route('prodi.index') }}" class="block py-2 text-amber-700 font-semibold bg-amber-200 rounded-r-full">📗 Data Prodi</a>
            </div>

            <button @click="suratOpen = !suratOpen"
                    class="w-full text-left py-2.5 px-6 flex justify-between items-center hover:bg-amber-200 transition rounded-r-full mt-2">
                <span>📄 Manajemen Surat</span>
                <svg :class="{'rotate-180': suratOpen}" class="w-4 h-4 transform transition-transform duration-300"
                     fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="suratOpen" x-collapse class="pl-10 text-sm space-y-1 mt-1 text-gray-700">
                <a href="#" class="block py-2 hover:text-gray-900 transition">📝 Jenis Surat</a>
                <a href="#" class="block py-2 hover:text-gray-900 transition">📁 Kategori Surat</a>
                <a href="#" class="block py-2 hover:text-gray-900 transition">📬 Data Pengajuan Surat</a>
                <a href="#" class="block py-2 hover:text-gray-900 transition">📂 Arsip Surat</a>
            </div>
        </nav>
    </div>

    <div class="p-4 border-t border-amber-200 bg-amber-50">
        <div class="flex items-center space-x-3">
            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=FCD34D&color=000"
                 alt="Avatar" class="w-10 h-10 rounded-full ring-2 ring-amber-300">
            <div>
                <p class="font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-700">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit"
                    class="w-full text-left bg-amber-300 hover:bg-amber-200 text-gray-900 font-semibold px-4 py-2 rounded-lg transition">
                🚪 Logout
            </button>
        </form>
    </div>
</aside>
