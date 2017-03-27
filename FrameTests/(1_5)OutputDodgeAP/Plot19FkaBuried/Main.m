myHexValues = {};
files = dir('*.jpeg');
for i = 1:numel(files)
jpegFileName = strcat('image-', num2str(i), '.jpeg');
imageData = imread(jpegFileName);
Mean = mean(reshape(imageData, size(imageData,1) * size(imageData,2), size(imageData,3)));
myHex = rgb2hex(Mean);
myHexValues{end+1} = myHex; 
Mean = 0;
myHex = 0;
end
results = string(myHexValues);

fid = fopen('results.txt','w');

fprintf(fid,'%s\n',results);

fclose(fid);