<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
               Group::make()->schema([
                /*
                Phương thức schema nhận vào một mảng (array) chứa các phần tử của biểu mẫu. 
                Mỗi phần tử có thể là một trường (input) hoặc các nhóm, phần khác nhau. 

                Group::make() tạo ra một nhóm (group) trong biểu mẫu.
                Phương thức schema([...]) định nghĩa các trường hoặc phần bên trong nhóm này.

                Section::make('Product Information') tạo ra một phần (section) với tiêu đề là "Product Information".
                Phương thức schema([...]) lại chứa các trường thông tin liên quan đến sản phẩm như name và slug.
                */
                Section::make('Product Information')->schema([
                    TextInput::make('name')
                    ->required()
                    ->live(onBlur:true)
                    ->afterStateUpdated(function(string $operation, $state, Set $set){
                        if($operation !== 'create'){
                            return;
                        }
                        $set('slug', Str::slug($state));
                    })
                    ->maxLength(255),

                    TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated()
                    ->unique(Product::class, 'slug', ignoreRecord:true),


                    // Description của sản phẩm
                    MarkdownEditor::make('description')
                    ->columnSpanFull()
                    // Cập nhật description của sản phẩm(products)
                    ->fileAttachmentsDirectory('products')

                    // Mỗi section bao gồm 2 column
                ])->columns(2),

                Section::make('Images')->schema([
                    FileUpload::make('images')
                    ->multiple()
                    // Chỉ định thư mục đích nơi các tệp hình ảnh sẽ được tải lên và lưu trữ. 
                    ->directory('products')
                    ->maxFiles(5)
                    // Cho phép người dùng sắp xếp lại thứ tự của các tệp hình ảnh sau khi tải lên
                    ->reorderable()
                ])
                //  Group sẽ chiếm hai column trong toàn bộ bố cục biểu mẫu.
               ])->columnSpan(2),
               Group::make()->schema([
                    Section::make('Price')->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            // Thêm tiền tố đơn vị tiền tệ trước dữ liệu nhập vào
                            ->prefix('VND')
                    ]),
                    Section::make('Associations')->schema([
                        Select::make('category_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        // Category chính là fn category() đã được định nghĩa quan hệ ở trong Product
                        // Hiểu đơn giản thị lấy name của category(ở trong file Product) để hiển thị ra 
                        ->relationship('category', 'name'),
                        Select::make('brand_id')
                        ->required()
                        ->searchable()
                        ->preload()
                        // Brand chính là fn Brand() đã được định nghĩa quan hệ ở trong Product
                        // Hiểu đơn giản thị lấy name của Brand(ở trong file Product) để hiển thị ra 
                        ->relationship('brand', 'name'),

                    ]),
                    Section::make('Status')->schema([
                        Toggle::make('in_stock')
                        ->required()
                        ->default(true),
                        Toggle::make('is_active')
                        ->required()
                        ->default(true),
                        Toggle::make('is_featured')
                        ->required(),
                        Toggle::make('on_sale')
                        ->required(),
                    ])
               ])->columnSpan(1)
               
            ])->columns(3);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('category.name')
                    ->sortable(),
                TextColumn::make('brand.name')
                ->sortable(),
                TextColumn::make('price')
                ->money('VND') 
                ->sortable(),

                IconColumn::make('is_featured')
                ->boolean(),
                IconColumn::make('on_sale')
                ->boolean(),
                IconColumn::make('in_stock')
                ->boolean(),
                IconColumn::make('is_active')
                ->boolean(),
                TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault:true),
                TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault:true)
                            
                      

            ])
            ->filters([
                //
                SelectFilter::make('category')
                ->relationship('category', 'name'),
                SelectFilter::make('brand')
                ->relationship('brand', 'name'),

            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
